# PK Tools

A collection of tools for playing 20x20 / Pecha Kucha presentations at events and on the web.

PK tools require PHP, they should run on most webservers and shared hosting services with a conventional LAMP stack. 

## Pecha Kucha Event Player
The Event Player allows playing a whole event. It lets you navigate between the presentations of the event and between the slides of each presention.

Pecha Kucha Event Player aims to help organisers of events that feature Pecha Kucha presentations. The event player requires PHP. If you want to run it locally (e.g. if a location does not have reliable internet access), you must have a webserver running on your machine and must place the files in an appropriate directory.

## Single Player
Single Player allows playing back individual 20x20 / Pecha Kucha presentations on the web. It features a slide counter and a clock that shows the time for each slide.

They player will show 20 Slides and play an audio recording of the talk. Recording and Slides are synchronized by setting a delay parameter.

All content ist stored in the file system, no database is required.

## Cheat App
Cheat App displays presentation notes along with the current and upcomig slide. Notes are stored in HTML files. 

