## How to run Residence CMS  in Docker Containers ##

1. Clone repository
```shell
git clone https://github.com/Coderberg/ResidenceCMS.git
cd ResidenceCMS
```

2. Create a `.env.local` file and update the `MAILER_DSN` variable to use Mailpit
```shell
cp .env .env.local
sed -i 's/MAILER_DSN=.*/MAILER_DSN=smtp:\/\/mailer:1025/' .env.local
```

3. Build and run containers
```shell
docker compose build --no-cache
docker compose up --pull always -d --wait
```

4. Install the app
```
docker compose exec -T php bin/console app:install
docker compose exec -T php bin/phpunit
```

5. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)

---

Additional services:

- PhpMyAdmin http://localhost:8081
- Mailpit http://localhost:8025

See more detailed documentation here https://github.com/dunglas/symfony-docker
