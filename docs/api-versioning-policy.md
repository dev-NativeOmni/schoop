# API Versioning Policy

Current public external API version: `v1`

## Route Format

All external API endpoints use a versioned prefix:

```text
/api/v1/...
```

## Compatibility Rules

- Adding new nullable fields is allowed in the same version.
- Adding new endpoints is allowed in the same version.
- Removing fields is a breaking change.
- Renaming fields is a breaking change.
- Changing response meaning or status behavior is a breaking change.
- Breaking changes must use a new prefix such as `/api/v2`.

## Deprecation

Deprecated endpoints must remain documented until a replacement is available and partner migration is complete. Deprecation notes must include replacement endpoint, planned removal window, and migration risk.

## Version Lifecycle

- `v1`: active.
- Future versions start as sandbox/internal before partner production use.
- Old versions are retired only after security review and partner notice.
