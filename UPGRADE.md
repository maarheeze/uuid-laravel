# Upgrade guide

Breaking changes per major version, newest first.

## 2.x to 3.0

No application code changes — `UuidCast::class` on a column and `HasUuidAsId` on a
model behave as they did, and still hand back a plain `Uuid`.

Both now name the identifier class they build. `UuidCast` takes it as a cast argument
and `HasUuidAsId` reads it from an overridable `idClass()`, each defaulting to `Uuid`,
so a model can use its own `UuidInterface` implementation instead of the generic one.
If you worked around the hard-coded `Uuid` by converting by hand on read, declare the
identifier in those two places and drop the conversion. See the README.

`HasUuidAsId::getKey()` is no longer `final`, so a model with a typed identifier can
narrow the return type to it. Models that override nothing are unaffected.

## 1.x to 2.0

Requires PHP 8.4 and Laravel 13. No application code changes.
