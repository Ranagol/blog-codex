# Testing Instructions

## General Rules

- Write automated tests for every new feature and bug fix.
- Use Laravel's existing testing framework and follow the conventions of existing tests.
- Test expected behavior, validation, authentication, authorization, and relevant database changes.
- Include tests for unauthorized actions and verify that rejected requests do not modify data.
- Do not remove or weaken existing tests just to make them pass.

## Running Tests

- Run relevant tests after each change.
- Run the full test suite with `sail artisan test` after completing a feature.
- Report failed, skipped, or unexecuted tests honestly. Never claim tests passed unless they actually passed.

## Blog-Specific Requirements

- Test that guests can read posts and comments but cannot create, edit, or delete them.
- Test that authenticated users can manage their own posts and comments but cannot edit or delete another user's content.
- Test required fields and the 255-character post title limit.
- Test post-comment relationships and permanent deletion of comments when their post is deleted.
- Test post pagination and ordering, with 10 newest posts per page and comments oldest first.

## Completion

Before reporting a feature as complete, summarize the tests run, their results, and any known gaps.
