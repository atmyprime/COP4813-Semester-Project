# Analytics Dashboard

## Key Metrics
- Total users
- New sign-ups (last 7 days / last 30 days)
- Total posts/requests
- Most common category
- Completion/matching rate

## Implementation
- Endpoint `/api/admin/stats`:
  - Returns JSON with the above metrics
- Frontend `/admin/analytics`:
  - Displays metrics in cards
  - Uses bar chart / pie chart for category breakdown
