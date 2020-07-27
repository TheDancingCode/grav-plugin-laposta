# Laposta Plugin

The **Laposta** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav) that adds a Laposta subscription action to your forms?

## Installation

Installing the Laposta plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install laposta

This will install the Laposta plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/laposta`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `laposta`. You can find these files on [GitHub](https://github.com/the-dancing-code/grav-plugin-laposta) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/laposta

> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com/the-dancing-code/grav-plugin-laposta/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/laposta/laposta.yaml` to `user/config/plugins/laposta.yaml` and only edit that copy.

Note that if you use the Admin Plugin, a file with your configuration named laposta.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
api_key:
default_list_id:
```

### api_key

Your Laposta API key. Can be found in [your dashboard](https://app.laposta.nl/config/c.connect/s.api/).

### default_list_id

The ID of the list subscribers will be added to by default, when no list is specified in the form definition. Your list ID's can be found in [your dashboard](https://app.laposta.nl/c.listconfig/s.settings/t.config/).

## Usage

Setup a form with the laposta action:

```yaml
form:
  name: subscribe
  fields:
    firstname:
      label: First name
      type: text
    surname:
      label: Surname
      type: text
    email:
      label: Email
      type: email
      validate:
        required: true
  buttons:
    submit:
      type: submit
      value: Subscribe
  process:
    - ip: true
    - laposta: true
    - message: Thank you for subscribing!
```

Add any fields from your Laposta list, but make sure to use the names as defined in [your dashboard](https://app.laposta.nl/c.listconfig/s.settings/t.fields/).

It is important that the `ip` action is enabled.

You can define a list ID in the laposta action if you wish to override the default list:

```yaml
process:
  - ip: true
  - laposta:
      list_id: xxxxxxxx
  - message: Thank you for subscribing!
```
