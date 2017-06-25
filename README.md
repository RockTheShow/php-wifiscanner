# php-wifiscanner

[![Build Status](https://travis-ci.org/orgasmix/php-wifiscanner.svg?branch=master)](https://travis-ci.org/orgasmix/php-wifiscanner)
[![Code Climate](https://codeclimate.com/github/firekraag/php-wifiscanner/badges/gpa.svg)](https://codeclimate.com/github/firekraag/php-wifiscanner)
[![License](https://img.shields.io/badge/license-GPLv2-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)

A reusable, object-oriented PHP port of [cunchem/gtk-wifiscanner](https://github.com/cunchem/gtk-wifiscanner)

### Context

When trying to establish a connection, your Wi-Fi-enabled device sends probes to figure out whether one of your usual, remembered networks is in range. These probes can be eavesdropped on. Since they contain your MAC address (unique identifier of your device) and might contain such usual networks names (ESSIDs) as well, they can effectively leak your privacy by disclosing locations you traveled to (e.g. This guy went to ACME-Airport-Wifi). Research showed that tight social links could be inferred from relatively small Wi-Fi data samples.

This program is the eavesdropper: it uses your Wi-Fi interface to sniff the surrounding traffic and report info gathered from picked up probes. Use it responsibly, it is meant as a demo of the privacy concerns active probing raises.

More info: http://confiance-numerique.clermont-universite.fr/Slides/M-Cunche-2014.pdf

### Preparation

This project uses the following binary dependencies: `tshark`.
Make sure to have them installed on your system.

Clone the project **and its deps** (note the *--recursive* option):

```shell
git clone --recursive https://github.com/firekraag/php-wifiscanner
```

Optionally, edit cli.php and set the constants to your desired values.
- `NOTIFY_TIMER`: the rate at which new probe requests are parsed. It should be safe to leave it to the default.
- `USE_SUDO` (experimental): whether to `sudo` the underlying commands. If set to `true` you do not need to run `cli.php` as root.
- `USE_MONITOR`: whether to capture WLAN packets in Monitor mode.

### Usage
**`[sudo ]php cli.php [--color] [--targeted-only] IFACE`**

Options:
- `--color`: if set, output will be ANSI-colored.
- `--targeted-only`: if set, wildcard probe requests will be ignored.

Arguments:
- `IFACE`: your wireless interface name (*e.g.* wlan0, en0...).

The application will start recording probe requests.

Hit `<ENTER>` to print the collected info.
Hit `q<ENTER>` to exit the program.

### Timezone
You should define your timezone in your `php.ini` file so that php-wifiscanner may display correct capture times.
If unset, `Europe/Paris` will be assumed.
