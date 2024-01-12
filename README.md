# hotelJava
# Java Island

Java, island of Indonesia lying southeast of Malaysia and Sumatra, south of Borneo (Kalimantan), and west of Bali. Java is home to roughly half of Indonesia’s population and dominates the country politically and economically. The capital of Java and of the country is Jakarta (formerly Batavia), which is also Indonesia’s largest city.

# Sahaja Resort

Place where every detail is designed for visitors' comfort, and every moment promises a sense of understated luxury.

[Click HERE to visit the webpage](https://panickaro.se/SahajaResort/)

# Code review

confirmAndPay.php: 15-16 - Are you sanitizing the user input somewhere? For example by using htmlspecialchars() or similar, to prevent a guest from inserting code via your form fields?

confirmAndPay.php: 23 - An alternative to using a JS-snippet to produce an alert on error could be to set a $_SESSION-variable with the error msg and then redirect the user to the previous page and display the error msg there.

confirmAndPay.php: 35 - Same as above, I might have missed where in your code sanitation is done though! You might also want to use trim() to prevent white space.

confirmAndPay.php: 57 - Instead of using $room['RoomType'] and appending ".png" there could perhaps be a separate imageURL-column in your database where the full image url is stored?

confirmAndPay.php: 62-65 - Instead of relying on <br> to separate html elements maybe add padding or margin to adjust spacing.

room.php: 52-54 - If using <br>-tags consider writing them as self-closing tags <br /> to ensure HTML validation.

footer.php: 0 - Could be named footer.html as it does not contain any php code.

databaseFunctions.php: 118-124 - When inserting or updating values in the database you could use the prepare/bindParam/execute-databaseFunctions to add another level of security to your database interaction. Instead of writing directly to the database!

functions.php: A general thought, maybe the file structure could be put into subfolders? With for example on folder containing the files with pure php (backend functionality) and another containing the app-pages the user interacts with!

room.php: 10 - Instead of setting the background img with inline styling you could consider moving it to your stylesheet.

finalNotes.final: 1 - Nice work, great structure and overall clarity!

## Authors

- [@lillastormen](https://github.com/lillastormen)


## License

[MIT]


## Color Reference

| Color             | Hex                                                                |
| ----------------- | ------------------------------------------------------------------ |
| lightFont| ![#f1eae2](https://via.placeholder.com/10/f1eae2?text=+) #f1eae2|
| Lemon-chiffon| ![#F9EFC9ff](https://via.placeholder.com/10/F9EFC9ff?text=+) #F9EFC9ff|
| Lion | ![#a48768de](https://via.placeholder.com/10/a48768de?text=+) #a48768de |
| Brown-sugar | ![#845d3e](https://via.placeholder.com/10/845d3e?text=+) #845d3e |
| Bookings | ![#c23b22](https://via.placeholder.com/10/c23b22?text=+) #c23b22 |
| Gunmetal | ![#1E2829ff](https://via.placeholder.com/10/1E2829ff?text=+) #1E2829ff |
| Brunswick-green| ![#071a15](https://via.placeholder.com/10/071a15?text=+) #071a15|
| Cadet | ![#596A6F](https://via.placeholder.com/10/596A6F?text=+) ##596A6F |


