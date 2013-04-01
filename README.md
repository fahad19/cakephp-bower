# CakePHP Bower Plugin

This plugin makes it possible for a [CakePHP](http://cakephp.org) application (along with its plugins) to utilize the power of [Twitter Bower](http://twitter.github.com/bower/) for package management.

The main responsibility of this plugin is to check what packages the current CakePHP application depends on, and download them to a specific directory under `app/webroot`.

# Requirement

The plugin is compatible with CakePHP 2.x. Clone/Download it to `app/Plugin/Bower`.

You will also need Bower installed globally via [npm](https://npmjs.org/package/bower):

    $ npm install -g bower

## Installation

When the plugin's shell is first run, it will automatically create a file at `app/.bowerrc` and a directory at `app/webroot/components/`. 

But to avoid any unexpected file permission issues, it is recommended you do the following manually:

* Copy `app/Plugin/Bower/.bowerrc.default` to `app/.bowerrc`
* Create a directory at `app/webroot/components`

And of course, load this plugin from your `app/Config/bootstrap.php`:

    CakePlugin::load('Bower');

## Usage

Given your CakePHP project depends on jQuery v1.9.1, it is expected you will have a file JSON file at `app/component.json` with this info:

    {
      "name": "my-cakephp-app",
      "version": "0.0.1",
      "dependencies": {
        "jquery": "~1.9.1"
      }
    }

Now if we run this command from the `app` directory, Bower will download jQuery to `app/webroot/components/jquery/`:

    $ ./Console/cake Bower.bower fetch app

Plugins can have their own `component.json` file too, and they need to be located at `app/Plugin/MyPlugin/component.json`.

To download dependencies of a plugin:

    $ ./Console/cake Bower.bower fetch MyPlugin

If you wish to download all the dependencies of your `app` and all your Plugins together in one go:

    $ ./Console/cake Bower.bower fetch .

## Configuration

You may wish to change the directory where packages are downloaded to, or even the name of the JSON file. You can do that by modifying the `app/.bowerrc` file.

## License

[MIT License](https://github.com/fahad19/cakephp-bower/blob/master/LICENSE.txt)