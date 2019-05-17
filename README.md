# Monorepo for zf1s (Zend Framework 1) packages

This is a monorepo of a fork of Zend Framework 1, made after it's reached its EOL.
All original framework's components have been split into individual packages, which can be installed separately with `composer`, e.g.
```
composer require zf1s/zend-acl
```

These packages will be maintained as long as we're using them, mainly just to keep it all working on new versions of PHP as they're released.
Currently everything should be compatible with **PHP 5.3-7.3**. _5.2 support is dropped._

They may also contain some fixes, either for long-standing bugs, which haven't made their way into zf1 official repo before EOL, or newly found ones
and (backwards compatible) adjustments (optimisations for composer autoloader mostly). Maybe even one or two new features.

### Changelog: [here](CHANGELOG.md)
Original README: [click](README.orig.md)



