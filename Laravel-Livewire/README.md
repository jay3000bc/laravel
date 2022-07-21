<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/alexsnowb/crm-on-laravel"><img src="https://travis-ci.org/alexsnowb/crm-on-laravel.svg?branch=master" alt="Build Status"></a>
<a href="https://codeclimate.com/github/alexsnowb/crm-on-laravel/maintainability"><img src="https://api.codeclimate.com/v1/badges/77e54ebb1bd64aece60f/maintainability" /></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Laravel 8 + Livewire

## About The Project

The project is based on real time chat application using Laravel and Livewire.

### About us

Pusher does Websocket infrastructure so you don't need to. [Check us out](https://pusher.com/).

<!-- GETTING STARTED -->
## Getting Started

If you don't want to follow the tutorial, and just want to get a local copy up and running, follow these steps.

### Prerequisites

You will need PHP, Composer and Node.js. For MacOS I recommend installing them with [Homebrew](https://brew.sh/). For Windows see instructions for [PHP](https://windows.php.net/download/), [Composer](https://getcomposer.org/doc/00-intro.md#installation-windows) and [Node](https://nodejs.org/en/download/).

### Installation

1. Clone this repo
2. Install Composer packages
   ```sh
   composer install
   ```
3. Initilise the database
    ```sh
    php artisan migrate
    ```
4. Compile the webpages and run it
    ```sh
    npm run dev
    php artisan serve
    ```

<!-- USAGE EXAMPLES -->
## Usage

Go to http://127.0.0.1:8000/, register a couple of users and start chatting!



## Demo Link

http://chat.nationalsociety.in/

## License

PusherSwift is released under the MIT license. See [LICENSE](https://github.com/pusher/laravel-chat/blob/master/LICENSE.md) for details.
