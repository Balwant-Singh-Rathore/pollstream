# Pollstream

Pollstream is a simple real-time polling platform built with **Laravel** and **WebSockets**.  
Admins can create polls, users can vote, and results update instantly across all connected clients.

This project demonstrates real-time event broadcasting using **Laravel Echo + WebSockets**, along with a clean Laravel architecture.

---

## Features

- Admin authentication (login/register)
- Create polls with multiple options
- Shareable public poll link
- One vote per user/IP per poll
- Real-time vote updates using WebSockets
- Live vote percentages and progress bars
- Admin dashboard to manage polls

---

## Tech Stack

- Laravel
- Laravel Broadcasting
- Laravel Echo
- Pusher compatible WebSockets (Soketi : https://soketi.app)
- TailwindCSS
- Alpine.js
- Turbo

---

# Installation

## 1. Clone the Repository

```bash
git clone https://github.com/Balwant-Singh-Rathore/pollstream.git
cd pollstream
```

---

## 2. Install Dependencies

```bash
composer install
npm install
```

## 3. Install Soketi (NodeJs 18)

```bash
 npm install -g @soketi/soketi
```

---

## 3. Environment Setup

Copy the environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

---

## 4. Configure Database

Update the `.env` file:

```
DB_DATABASE=pollstream
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

---

# WebSocket Configuration

This project uses **Pusher compatible WebSockets**.

Update `.env`:

```
PUSHER_APP_KEY=app-key
PUSHER_APP_ID=app-id
PUSHER_APP_SECRET=app-secret
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
PUSHER_APP_CLUSTER=mt1
```

Clear configuration cache:

```bash
php artisan optimize:clear
```

---

# Running the Project

You will need **three terminals**.

### Terminal 1 — Start Laravel

```bash
php artisan serve
```

---

### Terminal 2 — Start WebSocket Server

Using Soketi:

```bash
soketi start
```

---

### Terminal 3 — Start Vite

```bash
npm run dev
```

---

# Access the Application

To Access admin dashboard you can register yourself and get admin creds

```
http://127.0.0.1:8000/dashboard
```

Public poll page:

```
http://127.0.0.1:8000/poll/{poll_slug}
```

---

# Voting System

Each poll allows **only one vote per IP address**.

When a vote is submitted:

    1. The vote is stored in the database  
    2. Vote counts are updated  
    3. A broadcast event is triggered  
    4. All connected clients receive updates instantly  

---

# Real-Time Broadcasting

Votes trigger a Laravel event:

```
VoteCast
```

Broadcast channel:

```
poll.{pollId}
```

Clients subscribe to this channel and update:

- vote counts
- vote percentages
- progress bars

in real time without refreshing the page.

---

# Unit Testing

```
 php artisan test 
```
Or
```
 php artisan test tests/Feature/VoteTest.php
```

---

# Testing Real-Time Updates

- Open the same poll in **two browser tabs**
-  Cast a vote in one tab
-  The second tab should update instantly

---

# License

MIT License
