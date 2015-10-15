# php-wifiscanner

[![Build Status](https://travis-ci.org/firekraag/php-wifiscanner.svg?branch=master)](https://travis-ci.org/firekraag/php-wifiscanner)

A reusable, object-oriented PHP port of [cunchem/gtk-wifiscanner](https://github.com/cunchem/gtk-wifiscanner)

### Preparation

External, binary dependencies: `tshark`.
Make sure to have them installed on your system.

Fetch the project dependencies by running:

```shell
git submodule init
git submodule update
```

Edit cli.php and set the constants to your system values.
- `WLAN_ADAPTER`: your network interface ID
- `NOTIFY_TIMER`: the rate at which new probe requests are parsed. It should be safe to leave it to the default.
- `USE_SUDO` (experimental): whether to `sudo` the underlying commands. If set to `false` you may have to invoke the script itself with `sudo`, i.e. `sudo php cli.php`.
- `USE_MONITOR`: whether to capture WLAN packets in Monitor mode.

### Usage
**`php cli.php`**

The application will start recording probe requests.

Hit `<ENTER>` to print the collected info.
Hit `q<ENTER>` to exit the program.

### Timezone
You should define your timezone in your `php.ini` file so that php-wifiscanner may display correct capture times. If unset, `Europe/Paris` will be assumed.
