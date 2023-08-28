# Calendar app

A customer needs a simplified calendar application that needs integration into a larger
platform.

## Functional requirements

The following functional requirements must be satisfied:

1. The calendar granularity is whole days.
2. Entries in the calendar may cover one day, or several consecutive days
   1. **Bonus**, if the system allows entries for half-days
3. Calendar entries have a state:
   1. Requested
   2. Tentative
   3. Booked
4. The user must be able to enumerate their agenda, from the current day a month (30 days) into the future
   1. **BONUS:** The time span is configurable.
5. The user must be able to add, update, and delete entries.
   1. Updates may change the state, start date, end date, or any combination.
   2. If an update would cause a violation of any of the rules below, the system must reject the change with a descriptive error message.
6. The following rules apply for calendar entries when they are created or modified.
   1. “Booked” entries may not overlap with any other entry.
   2. Once “booked”, entries can no longer change state.
   3. **BONUS RULE:** “Tentative” entries may not overlap with more than 4 other entries.
7. **BONUS REQUIREMENT:** When queried, the system must be able to respond whether the user is “available” at a given timeframe (i.e. whether a hypothetical calendar entry would not violate the rules given in requirement 6.

## Non-functional requirements

As the system will be embedded in a larger platform, it must fit into the architecture, and a prescribed set of components and design patterns must be used:

1. The system must use a suitable document structure (schema validation not required)
2. The service must be developed using Node.js or PHP
3. The service must expose a REST API that makes proper use of HTTP verbs and response status codes.
4. Message content is application/json at all times, except where message bodies are not needed (e.g., for GET requests)
5. Security considerations are not a concern except for basic user input validation (empty/not empty, format checking)
   
## Assignment

* The solution must include the database, and the service, including all relevant modules and plugins used. A GUI is not required.
* Concise instructions how to start the system should be provided.
* The solution must accept proper requests sent to its endpoints, be it curl, Postman, or any other tool simulating a UI.
* Documentation, including source code comments, is not required, but beneficial.
* The mandatory requirements must be implemented first, before any bonus requirements are considered.
* The assignment should be finished in 6 hours.

Using the service-repository design pattern because it’s clean and sustainable. The concept of repositories and services ensures that you write reusable code and helps to keep your controller as simple as possible making them more readable.

https://dev.to/safventure23/implement-crud-with-laravel-service-repository-pattern-1dkl
