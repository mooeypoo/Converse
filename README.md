# Converse

A light weight discussion system with javascript interactive layer.

## Purpose
Develop a light weight and flexible discussion system for focused discussions. This is not a replacement for full blown forums, but rather a specific solution for more contextual group discussions.

## Features
* Create discussions with title, descripion and content.
* Discussions should hold child discussions ("topics") and these should also have child discussions ("reply threads")
* The structure of the discussion is preserved by context in the database; each child is connected to its original parent unless moved elsewhere.
* The structure of the is presented to users according to
maximum nesting. The nesting can change depending on the
level of the display.
** For example, consider a discussion with nesting level of 4. Looking at a thread with 10 levels of nesting, the user will only see 4 levels, the fourth displaying all subsequent replies ordered by date. Each reply still preserves its context, but is displayed without further nesting at that level. However, if we reload to display the reply thread at level #5, which, seemingly, has no children, the specific display will show its nesting children 4 levels down again.
*** This makes sure that the user is not completely lost with endlessly nested replies -- and yet, the context of the discussion is preserved, and can be accessed by looking through deeper and deeper views, if needed.
* The system should work with server-side rendering (for computers who either block Javascript or don't have it)
* A top layer of Javascript should add interactive operations.
* The system should supply an accessible API for the operations against the database.

## Software Status
This is a side project I'm working on to re-develop my php skills. As such, there are a few things to note:
* The software is not ready for use! It is still being developed, and is extremely unstable. Please beware and don't put this code anywhere close to production servers or anywhere that actually matters until it's stable.
* Since this is somewhat of a learning experience, please be aware there may be (and probably are) some atrocious bits of php code. If you see them, please comment or add an issue to the list. And feel free to send a pull request!
* There are several key places where I've noted the software should be optimized. For the moment, the thinking is to Make It Work and create a proper infrastructure. Optimization is next.

## Bugs and features
Feel free to report bugs, feature requests, and/or any other comment. And, of course, pull requests are welcome.

## License
This code is licensed MIT. Feel free to fork it and do what you will, just please credit the author(s) in the list. Better yet, if you're considering forking, please consider joining the project or sending pull requests.

### Author(s)
Moriel Schottlender
