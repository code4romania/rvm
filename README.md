# RVM

[![GitHub contributors][ico-contributors]][link-contributors]
[![GitHub last commit][ico-last-commit]][link-last-commit]
[![License: MPL 2.0][ico-license]][link-license]

[Contributing](#contributing) | [Built with](#built-with) | [Development](#development) | [Deployment](#deployment) | [Feedback](#feedback) | [License](#license) | [About Code for Romania](#about-code-for-romania)

## Contributing

This project is built by amazing volunteers and you can be one of them! Here's a list of ways in [which you can contribute to this project][link-contributing].

## Built With
-   Laravel
-   Tailwind CSS
-   Filament

### Requirements
-   PHP 8.1+
-   Nginx
-   MySQL 8.0

## Development
This project uses Laravel Sail, Laravel's default Docker development environment.

After running the [initial setup](#initial-setup), run this command to start up the environment:
```sh
./vendor/bin/sail up -d
```

and then this command to rebuild the css / js assets on change:

```sh
./vendor/bin/sail npm run dev
```

### Initial setup

```sh
# 1. Install composer dependencies
docker run --rm -v ${PWD}:/app -w /app composer:latest composer install --ignore-platform-reqs --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# 2. Copy the environment variables file
cp .env.example .env

# 3. Start the application
./vendor/bin/sail up -d

# 4. Install npm dependencies
./vendor/bin/sail npm ci

# 5. Build the frontend
./vendor/bin/sail npm run build

# 6. Generate the app secret key
./vendor/bin/sail artisan key:generate

# 7. Migrate and seed the database
./vendor/bin/sail artisan migrate:fresh --seed
```

For more information on Laravel Sail, check out the [official documentation](https://laravel.com/docs/9.x/sail).

## Deployment

The fastest way to deploy RVM is by using our first-party [Docker image](https://hub.docker.com/r/code4romania/rvm).

### Prerequisites

#### Generate an application key

To generate an application key, you can run the following command in your terminal.

```sh
docker run --rm -it code4romania/rvm php artisan key:generate --show
```

The generated application key will look like this:

```
base64:yEtz1eacKMwq0iVT5BUjMMvcn4OAD7QpCgz1yDoXroE=
```

### Running the image

Download the [example `docker-compose.yml`](docs/examples/docker-compose.yml) and configure the environment variables. Here's where you use the `APP_KEY` generated earlier. After you're done, save the file and run it with:

```sh
docker-compose up -d
```

## Feedback

* Request a new feature on GitHub.
* Vote for popular feature requests.
* File a bug in GitHub Issues.
* Email us with other feedback contact@code4.ro

## License

This project is licensed under the MPL 2.0 License - see the [LICENSE](LICENSE) file for details

## About Code for Romania

Started in 2016, Code for Romania is a civic tech NGO, official member of the Code for All network. We have a community of around 2.000 volunteers (developers, ux/ui, communications, data scientists, graphic designers, devops, it security and more) who work pro-bono for developing digital solutions to solve social problems. #techforsocialgood. If you want to learn more details about our projects [visit our site][link-code4] or if you want to talk to one of our staff members, please e-mail us at contact@code4.ro.

Last, but not least, we rely on donations to ensure the infrastructure, logistics and management of our community that is widely spread across 11 timezones, coding for social change to make Romania and the world a better place. If you want to support us, [you can do it here][link-donate].


[ico-contributors]: https://img.shields.io/github/contributors/code4romania/asistent-medical-comunitar.svg?style=for-the-badge
[ico-last-commit]: https://img.shields.io/github/last-commit/code4romania/asistent-medical-comunitar.svg?style=for-the-badge
[ico-license]: https://img.shields.io/badge/license-MPL%202.0-brightgreen.svg?style=for-the-badge

[link-contributors]: https://github.com/code4romania/asistent-medical-comunitar/graphs/contributors
[link-last-commit]: https://github.com/code4romania/asistent-medical-comunitar/commits/main
[link-license]: https://opensource.org/licenses/MPL-2.0
[link-contributing]: https://github.com/code4romania/.github/blob/main/CONTRIBUTING.md

[link-code4]: https://www.code4.ro/en/
[link-donate]: https://code4.ro/en/donate/
