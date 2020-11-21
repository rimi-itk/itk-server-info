# ITK server info – Server

```sh
git clone --branch=server https://github.com/rimi-itk/itk-server-info
```

## Installation

```sh
cp .env .env.local
```

Edit `.env.local` as needed.

## Running

```sh
bin/run
```

## Coding standards

Install [`ShellCheck`](https://www.shellcheck.net/):

```sh
brew install shellcheck
```

Check the code:

```sh
shellcheck bin/*
```
