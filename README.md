# CS50x_Final_Project

It's not pretty, it's a less than half-hearted attempt at MVC, and has only a modicum of error checking.  But it works.  And I'm proud of what I did.  And it only took me three weeks.  :S

Given an origin and destination in the U.S., this web application finds the best places to fuel a class 8 diesel truck.

It uses Google's Javascript API to access their Directions Service, Distance Matrix API, and Places Library.  It will keep track of your user settings (current fuel level, etc) via PHP sessions and it accesses data from a MySQL database that has been populated by web scraping fuel price/location info from major diesel fuel retailers.

It then determines optimal fuel stops along your selected route.

This is all served from localhost as I have yet to transfer it to a commercial server.

You will need a Google API Key to actually use this.

Alas, I would have liked to do my final project in C, but I had the problem in mind first, and given the tools available I took what I thought was the appropriate path.

I would like to thank:
David Malan, and all of the current and former staff who put together this amazing course - not the least of whom are Zamyla, Allison, Nate, Tommy, Rob, Daven, Glenn, Christopher, Chris, Bannus, Kevin, Mark, Jackson, and Lauren.
Also, social moderators and active members like Kareem, Brenda, and Cliff.
Thank you to Harvard, EdX, and hopefully soon LaunchCode!
