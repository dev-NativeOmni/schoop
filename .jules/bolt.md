## 2025-02-06 - Dashboard Aggregation Bottleneck
**Learning:** Dashboard endpoints with multiple `count()` aggregate queries on large tables without explicit caching (like `TenantDashboardController`) create a significant performance bottleneck.
**Action:** Always implement caching (e.g., `Cache::remember()` for 15 minutes) for multiple large aggregate queries on dashboards, mirroring the existing `DashboardController` pattern to avoid heavy load on the database.
