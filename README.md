# RWatch

A simple CLI tool to simplify the process of starting `npm run watch` for my projects on our dev server.

It gets a list of all symlinks in the user's home directory on the server and prompts the user to select one. (It is made this way because that's how I have my projects set up. I have a symlink from my home directory to each project. In the future, I will add support for other ways of supplying a list of projects.)

It then uses SSH to start `npm run watch` on the selected project.

Currently only supports using command line arguments to supply the server and username.

In the future, I will add support for using a config file, and prompt the user for the server and username if they are not supplied.

## Requirements

- PHP 8.4

## Installation

```bash
composer install
```

## Building the executable

To create the standalone executable PHAR file, you first need to install [Box](https://github.com/box-project/box/tree/main) as per their documentation.

With Box installed, cd into rwatch's root directory (where the `box.json` file resides) and do:

```bash
box compile
```

This will generate an executable .phar file named `rwatch` (note the omitted extension) in the project root.

You can then move the file to your PATH.

## Usage

Assuming the file is in your PATH, you can run it like this:

```bash
rwatch --server=server.example.com --username=johndoe
```

## Testing

```bash
composer test
```

## Wishlist

- [ ] Rename the app flow steps ("Command" is confusable with Command/Query pattern)
- [ ] Use a config file instead of command line arguments (WIP)
- [ ] Prompt the user for the server and username if they are not supplied
- [ ] Other ways to supply a list of projects (e.g. specifying a directory of projects)
- [ ] Command line option to specify the project (skips the interactive selection)
- [ ] Cache the list of projects, create a command line option to refresh the cache

## Contributing

Contributions are welcome! Please open an issue or submit a pull request.

## License

[GPL-3.0](https://choosealicense.com/licenses/gpl-3.0/)
