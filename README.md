# Idle-Blog
## Description

This project is an example blogging website where users are able to post and discover new and interesting blogs. The features include:
- Login/Register
- Creating blogs, with markdown for sub headings and custom blog images
- Editing and deleting blogs
- Latest blog (edited or created) is displayed on each user profile
- Latest blog from all users is displayed on front page (index/discover page)
- Users can change any of their details, including a profile picture
- Search all blogs with keyword search
- Search specific user profile for blog
- Search for user by specifying '@<username>' in search bar
- Password reset via email ('Forgot Password' button on login)

Note: After using the filesystem for storing user and blog images it had been quite impractical, so in future projects I will most likely use sql's BLOBs, as its much easier to ensure image content and name integrity.

## Dependencies / Tools Used

XAMPP for local hosting (easy to use and set up): https://www.apachefriends.org/download.html

Frontend:
- HTML ( + rendered Twig templates)
- JQuery ( + ajax for immediate image upload previews)
- Bootstrap + some custom css

Backend:
- Php - version 8.0.3
- MySQL for database + PhpMyAdmin
- Twig - version ^3.0

## Datebase Design

![](/repo_imgs/dbdiagram.JPG)

This is the structure of the MySQL database the website uses. 

Notes:
- Some columns have default values, such as images with default images and expiration/creation dates (either today or tomorrow).
- There is also an SQL Event that runs each day which deletes all password reset tokens if their value in the expiration date column is ever less than the CURRENT_TIMESTAMP

## How it Works

For the sake of space I'll mention only some of the interesting features of the website

![](/repo_imgs/register.JPG)

At first you the have the option of registering or logging in, you can still use the site without logging in and read blogs, but you can't create any

![](/repo_imgs/discover.JPG)

If you log in and go to the home/discover page, you'll see the latest blog posted among all users. In the above another user recently created a blog about swedish food and it displays their username (if clicked it will go to their profile) and the last date and time it was modified

![](/repo_imgs/search.JPG)

Using the search bar you can search for blogs from all users (if on the discover page) or if you go to a specific user's page you can search all of their blogs only

![](/repo_imgs/profile_personal.JPG)

If you click on your profile at the top left when you log in it will take you to your profile page. This is where you can search your own blogs and also edit or delete the blog that is currently displayed (or displayed through search). Clicking your profile picture also lets you upload a new one

![](/repo_imgs/profile_other.JPG)

The above is how other users would see your page, they too can search all your posted blogs.
