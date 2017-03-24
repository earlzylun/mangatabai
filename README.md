# MANGA TA BAI #

MANGA TA BAI is a phrase in bisaya that means "Let's Manga Dude".

Manga is Japanese Comics, this project will let you do the following:
* Download Manga Chapters
* Read Manga Chapters with some convenient navigation

I personally use this project in my local computer to be up to date with some of my favorite Manga Titles and I archive them in the process.


## How do I download Manga Chapters? ##

*Please duly note that since this is just a personal project, I haven't ventured in creating a UI for downloading manga chapters.*

### To download a Manga Chapter: ###
* Get the Manga Chapter url from any of the 2 supported websites, mangapanda.com and mangahere.co. e.g.: http://www.mangapanda.com/shingeki-no-kyojin/91
* Append this string in the URL: ?get_manga=CHAPTER_URL e.g.: http://localhost/mangatabai/?get_manga=http://www.mangapanda.com/shingeki-no-kyojin/91

## What is the main feature present when I read a Manga Chapter? ##

### Easy navigation ###
When you read a Manga Chapter you normally use your mouse-wheel to navigate through the pages. With this project you can use your Left and Right arrow to read them comfortably.

Here are the behavior of each arrow key:

Left Arrow:
* Brings you to the top of the current page you are in.
* If you're at the top of the page, you'll be brought to the top of the previous page.

Right Arrow:
* Brings you to the bottom of the current page.
* If you're already at the bottom of the page, you'll be brought to the top of the next page.