# Plateau Explorer
<hr>

> Plateau Explorer is an intelligent command line application to control some rovers to explore a plateau on Mars 

> The Application is written by `Laravel`, if you are not familiar with the environment please check the link below:

[Laravel Installation](https://laravel.com/docs/8.x/installation)

> Also be sure that you have `php 8` and `composer`
## Topics
- [Description](#Description)
- [How is it work](#How-is-it-work)
- [Installation guid](#Installation-Guid)

# Description
<hr>

A squad of robotic rovers is to be landed by NASA on a plateau on Mars.

This plateau, which is curiously rectangular, must be navigated by the rovers so that their on-board cameras can get a complete view of the surrounding terrain to send back to Earth.

A rover's position is represented by a combination of x and y coordinates and a letter

representing one of the four cardinal compass points. The plateau is divided up into a grid to

simplify navigation. An example position might be 0, 0, N, which means the rover is in the

bottom left corner and facing North.

In order to control a rover, NASA sends a simple string of letters. The possible letters are 'L', 'R'

and 'M'. 'L' and 'R' make the rover spin 90 degrees left or right respectively, without moving

from its current spot.

'M' means move forward one grid point and maintain the same heading.

Assume that the square directly North from (x, y) is (x, y+1).

Input:

The first line of input is the upper-right coordinates of the plateau, the lower-left coordinates are

assumed to be 0,0.

The rest of the input is information pertaining to the rovers that have been deployed. Each rover

has two lines of input. The first line gives the rover's position, and the second line is a series of

instructions telling the rover how to explore the plateau.

The position is made up of two integers and a letter separated by spaces, corresponding to the

x and y coordinates and the rover's orientation.

Each rover will be finished sequentially, which means that the second rover won't start to move

until the first one has finished moving.

<b> Output:

The output for each rover should be its final coordinates and heading.

## How is it work ?
<hr>

### Let`s imagine we want to send two rovers on a Plateau with coordinates (x=5,y=5) size:
####so the location of (firstRover) is (0,2,EAST)
####and the location of (secondRover) is (2,1,WEST)
|  |  |  |  | |
| :----------- | :------------: | :------------: | :------------:|:------------: | 
|    |     | |  |  |
|  (FirstRover) ->  |     | |  |  |
|    |     | <- (SecondRover) |  |  |
|    |     |  |  |  |



### And if we send move command for both of them their position would be like this :

|  |  |  |  | |
| :----------- | :------------: | :------------: | :------------:|:------------: | 
|    |     | |  |  |
|    |   (FirstRover) ->  | |  |  |
|    |   <- (SecondRover)  |  |  |  |
|    |     |  |  |  |

<hr>

### So to have above test, system will start asking you some questions
```sh
$ php artisan rovers:explore
$ Lets start with the size of our plateau, could you please enter the max X Y? => example : 6 6:
> 5 5
$ Ok I named one of your rovers 'Eleonore Grant' now let me know what is the location of Eleonore Grant ? example 1 3 n/e/s/w:
> 0 2 e
$ Ok I`ve got the location of Eleonore Grant, So what are the moves for that ? example MMRMMRMRRM:
> m
$ Do you have another rover? (yes/no) [no]:
> yes
$ Ok I named one of your rovers 'Test Name' now let me know what is the location of Test Name ? example 1 3 n/e/s/w:
> 2 1 w
$ Ok I`ve got the location of Test Name, So what are the moves for that ? example MMRMMRMRRM:
> m
$ Do you have another rover? (yes/no) [no]:
> no
======  WWwooooww, That`s it, I hope you enjoy the results  =====

            Rover_name: Eleonore Grant,
            Rover_currentPosition: {"x":1,"y":2}
            Rover_face_orientation: East
            ---------------------------------------
            

            Rover_name: Test Name,
            Rover_currentPosition: {"x":1,"y":1}
            Rover_face_orientation: West
            ---------------------------------------
```

# Installation Guid

### Clone project From Git

```sh
$ mkdir plateau_explorer
$ cd plateau_explorer
$ git clone "https://github.com/mhmdnz/plateau_explorer.git" .
$ composer install
```
### Start playing

To start program you should run
```sh
$ php artisan rovers:explore
```
![Alt text](img_1.png?raw=true "Title")
### Run Tests

The Feature/Unit tests are available in tests directory 
```sh
//you can run all the tests by running below command in the project root
$ php artisan test
```
![Alt text](img.png?raw=true "Title")
```sh
//you can run only one test by using this command
$ php artisan test {testAddress}
```
