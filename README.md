# Weather Alert System

The Weather Alert System is a web application that notifies users about weather changes based on custom threshold levels. Users can define their own weather preferences and receive real-time notifications when the specified conditions are met.

## Features

- Customizable weather thresholds for notifications.
- Real-time alerts via email.
- Easy integration with third-party weather APIs.
- Dockerized environment for seamless deployment.

## Setup Instructions

To get started with the Weather Alert System, follow these steps:

1. Clone the repository:

   ```bash
   git clone <repository_url>
   ```

2. Navigate to the project directory:

   ```bash
   cd weather-alert-app
   ```
3. Put the database credentials to the .env file

4. Install composer:

   ```bash
   composer install
   ```

5. Start the application using Laravel Sail:

   ```bash
   ./vendor/bin/sail up
   ```

4. Open your browser and navigate to `http://localhost` to access the application.

## Mailhog Service

The Weather Alert System uses Mailhog for local email testing. To view captured emails, navigate to the Mailhog server:

```
http://localhost:8025/
```

## Contributing

We welcome contributions to the Weather Alert System! Please submit a pull request or open an issue if you'd like to contribute.

## License

This project is open-source and available under the [MIT license](https://opensource.org/licenses/MIT).
