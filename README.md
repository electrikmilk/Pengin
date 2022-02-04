![Screenshot](https://i.imgur.com/eRpUv8W.png)

# ![Logo](https://raw.githubusercontent.com/electrikmilk/Pengin/main/favicon/favicon-32x32.png) Pengin

This is my first attempt at a Social Network written in PHP with a basic front-end. This code is from late 2020.

I made this during the pandemic as a distraction/hobby/learning project. Hobby project as in I've always found the idea of doing a social network fun since I was young, and now that I have some idea of how to actually make one, I took a stab at it. Learning project as in, I hadn't really done much sites outside of my job since I got my first tech job and I needed to start fresh to apply things I had learned, and also learn by doing.

## Features

- Time Limit
- Search and autocomplete system
- Custom UI with modal and menu systems
- Responsive and supports mobile
- Lazy loading
- Verified profiles
- Posts feed
- Link scraping for previews
- Context menus for profiles, posts, threads, etc.
- Realtime Notifications: not through Web APIs but the favicon changes and a count appears above the notifications icon, plus a toast message appears
- Notifications page with icons for types of alerts (followed, replied, etc.)
- Loading pages through ajax, and changing the url so that it would load the same page once you refresh
- Discussion boards with threads, save threads for later
- Create posts on your profile and post threads in boards and reply to threads (500 chars, Markdown support)
- Like (with a little animation), Reply, Repost (with quote)
- Image proxy for post media and profile pictures
- Giphy integration for automatic cover images on topics and for posting.
- Emoji Support 😎
- Multilingual and timezone support
- Profanity filter (how much you'd like to see)
- One time edit
- Pin to profile
- Trending threads
- ACTUALLY deletes your posts
- Following system
- Blocking system
  - Silence: Muting them basically
  - Remove from followers
  - Block
- Back button system
- Settings page with categories
- NSFW flags on threads
- Privacy
  - In-depth privacy settings
  - Special paragraph class just for text related to privacy
  - Your profile can be as private or as public as you'd like.
  - Per-post privacy settings

## Now you've heard the good, here's the bad...and the ugly.

Yeah...probably don't host this unless you're ready to change how the accounts system works and how the database is accessed and fix a lot of bugs. I've learned a lot about PHP programming in the last two years since the pandemic started and alot of this would need a good redo for security and bugs. Perhaps I'll make changes to this when I have the time to, so that this is has more proper implementations.

This is mainly here so that maybe others can learn something from this version of the project.

If I do host this in the future, I'd likely take down this source for security reasons, but either way the database and accounts system would be rewritten entirely.

## Why not host this myself?

Obviously, maintaining a social network is a cumbersome undertaking, and I don't think I'm in the position to do that as one developer.
