## Synopsis

This project was created by Vincent Scavetta as the final project for the
Spring 2017 Advance Web Programming course at Rowan University. The project goals
were to build an image sharing website that users could create an account, upload
images, and leave comments for others to see. These requirements were implemented
using PHP, MySQL, HTML, JavaScript, and CSS.

## Features
- Users can register with a username, email, and password. Usernames must be unique.
- In the chance that a user forgets his or her password, a password recovery link is sent to the users email.
- Users can upload images with captions and leave comments on each uploaded image.
- The owner of the image can delete any comments that they do not like.
- All pictures and comments are tagged with a date.
- Users can be tagged with @.
- Tagging a user with @ creates a link to that users profile
- A users profile currently displays the date the user was created as well as their last login time
- Hashtags can be put on pictures when uploading. These hashtags can be searched for.
- Multiple hashtags can be searched for at once in the top search bar. Only photos with all searched hashtags will appear.
- Users can delete their account if they see fit. When they do, this action is irreversible and removes their uploaded photo. Their comments will remain however they are marked with [deleted].
- Users can change their password by viewing their profile page and clicking "change password".
- Users can block/unblock other users. A blocked user cannot comment on any photos uploaded by the blocker.
- Users can view this website from their phones as well as upload photos* tested on an iPhone 7, YMMV
- Home page has "Infinite" scroll. It will load 10 images at a time until there are no more photos.

## Known Limitations

- Accounts are pre-activated without needing to click the emailed link.
  - This was done to make testing/grading easier, however in a production enviroment accounts would be defaulted to inactive.
- Only 10 comments can be shown at a single time on a photo.
  - I would add a smaller button to the comment box that says "Load more comments".
  - This button would work similar to how the infinite scroll works by using an offset.
  - This button would only be inserted into the HTML if there were more comments to view.
- The users profile page is bare at the moment.
  - There are plans to show recent images that user has uploaded.
  - This would be done by using a simple sql query that only selects photos uploaded by that user. The list of photos would then be handed off to the photo generation script.
- Admin accounts are not implemented.
  - I would implement this by adding another column to the users table that indicates user level. (int)
  - If the user had the highest level, buttons for deletion would exist on every possible item such as comments, user profiles, and photos.
  - These buttons already are dynamically added to the HTML depending on who is viewing the page so this would take just the addition of another conditional.
