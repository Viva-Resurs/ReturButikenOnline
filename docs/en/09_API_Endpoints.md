# API Endpoints

## Models
HTTP Method | URI | Action
--- | --- | ---
GET | sections | List sections
POST | sections | Create section
PUT | sections/:id | Update section
DELETE | sections/:id | Delete section
GET | articles | List articles
POST | articles | Create article
PUT | articles/:id | Update article
DELETE | articles/:id | Delete article
GET | categories | List categories
POST | categories | Create category
PUT | categories/:id | Update category
DELETE | categories/:id | Delete category
GET | users | List users
POST | users | Create user
PUT | users/:id | Update user
DELETE | users/:id | Delete user


## Current user & helpers

HTTP Method | URI | Action
--- | --- | ---
GET | overview | Get overview (sections > contacts > articles)
GET | overview/my | Get articles created by current user
GET | user | Get current user
GET | contacts | Get available contacts in section
GET | roles | Get available roles (for admin)
GET | token | Get XSRF-TOKEN
