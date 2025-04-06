# Recruitify
This Laravel application is designed to help tech recruiters efficiently discover and connect with developer talent.

The system searches for GitHub users based on custom keywords and filters, stores their profiles in the database, and provides tools to enrich this data with additional context—both from GitHub and LinkedIn.

By separating the flow into distinct steps and jobs, this app gives you full control over each stage of the enrichment pipeline—ideal for scaling, debugging, or running in scheduled batches.

Whether you're building a sourcing tool, scouting open-source contributors, or automating outreach, this profiler helps you move faster and smarter.


## ⚙️ How It Works

### 1. 🔍 Search GitHub Users

Run an artisan command to search for GitHub users based on specific keywords and filters.
Basic profile data (e.g. username, GitHub ID, URL) is stored in the database.

### 2. 📦 Enrich GitHub Profiles

Trigger a second artisan command that dispatches jobs to fetch full user data via the GitHub API.
This includes details like bio, location, public repos, followers, and more.
### 3. 🔗 Discover LinkedIn Profiles

If a GitHub profile contains a name, a background job is dispatched to search for potential LinkedIn profiles using the Google Search API.
Found LinkedIn URLs are stored alongside the GitHub data in the database.

## ✨ Highlights

### 1. Guzzle Client Integration
Utilizes Guzzle HTTP client for seamless communication with the Google and GitHub APIs.
Additionally, FakeClients are included for both APIs, which use data fixtures for testing and development purposes, allowing you to simulate API responses without making real API calls.

### 2. Rate Limiting Middleware

- Basic Rate Limiter: Limits requests based on a predefined request count.
- Response Header Rate Limiter : Dynamically adjusts the request rate based on response headers, optimizing API call efficiency.

### 3. CSV Export
Allows exporting user data in a structured CSV format for easy reporting and analysis.

### 4. Profile Data Datatable
Interactive datatable for displaying profiles, allowing users to view, filter, and sort the fetched data with ease.

## Installation
```bash


```

