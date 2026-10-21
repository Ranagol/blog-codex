Project Requirements
Users
Guests can view posts and comments. Authenticated users can create posts and comments. Users can edit and delete only their own posts and comments. 
Posts
Authenticated users can create, view, edit, and delete their posts. Each post has a title, content, author, and creation/update timestamps. Titles and content are required.
Comments
Users can add, view, edit, and delete comments. Each comment belongs to a post and an author. Comment content must be non-empty and no longer than 500 characters. Deleting a post also deletes its comments.
Business Rules
Use the existing Laravel Breeze authentication. Guests cannot create, edit, or delete content. Validate all input on the server. Enforce ownership and authorization on the server. Deny unauthorized actions without modifying data; no particular HTTP status code is required. Use the existing Blade and Tailwind CSS stack.
