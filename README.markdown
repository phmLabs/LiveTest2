# LiveTest2

*a tool to run automated response tests against a set of urls*

[![Build Status](https://secure.travis-ci.org/phmLabs/LiveTest2.png)](http://travis-ci.org/phmLabs/LiveTest2)


# Installation

## Installation as Dependency with Composer

edit your `composer.json`

``` json
"require" : {
    "phmlabs/livetest2" : "dev-master"
}
```

or let composer do the job

```
$ php composer.phar require phmlabs/livetest2
```

then you have the binary installed to **COMPOSER_BIN_DIR**

```
$ bin/livetest
```

## Installation as Standalone Application

```
$ git clone https://github.com/phmLabs/LiveTest2.git
$ cd LiveTest2
$ bin/vendors
$ bin/livetest
```

## Installation as PHAR (WIP)

simply download the PHAR, or compile it yourself (see Building the PHAR)

```
$ wget http://livetest.phmlabs.com/phar
$ php livetest.phar
```

# Usage

*we use the PHAR to show the examples*

## Running the Testsuite

```
$ php livetest.phar run path/to/testsuite.yml path/to/config.yml
```

## Currently implemented TestCases

**TODO**

see *LiveTest\TestCase*

## Building the PHAR

**TODO**

to build the PHAR simply run the following command

```
$ bin/compile
```

# Tests

simply install the application as **standalone**

```
$ phpunit test
```

see TravisCI for recent builds http://travis-ci.org/phmLabs/LiveTest2

# TODO

see https://github.com/phmLabs/LiveTest2/issues
