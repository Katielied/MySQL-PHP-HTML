// DOM (Document Object Model) Manipulation
// DOM Manipulation is the process of using Javascript
// to interact with and modify the structure, style, and
// content of a web page.  By manipulating the DOM, we 
// can change aspects of an HTML document, such as elements,
// attributes and text.

// With DOM Manipulation, we can...
// - Dynamically update content, changing things like
// text or images without reloading the page.
// - Modify attributes and styles - You could change the
// the src attribute of an image, or its CSS (color, size, etc)
// - We can add elements to the DOM or remove existing elements.
// - Handle user events by attaching "Event Listeners" to elements
// We can respond to user interactions like mouse clicks, key inputs, etc.

// Math - JavaScript's way of manipulating numbers with methods.
// Math.random() Generates a random number between 0 and 1
// Math.floor() Rounds a number down (i.e. 1.12345 becomes 1)

//Getting random word from array.
const words = ["creak","flair","eaten","dozen","lucky","extra","otter","tried","bored"];
let randomIndex = Math.floor(Math.random() * 9);
let randomWord = words[randomIndex];
console.log(`random word: ${randomWord}`);

const guesses = document.querySelector('.guesses');
const lastResult = document.querySelector(".lastResult");
const isInvalidFeedback = document.querySelector(".isInvalidFeedback");

const guessSubmit = document.querySelector("#guessSubmit");
const guessField = document.querySelector("#guessField");

let guessCount = 1;
let restartButton;

function checkGuess(event) {
    event.preventDefault();

    //Trimming the user string input.
    const userGuess = String(guessField.value).trim();


    try
    {
        //Checking that user input does not contain numbers or letters.
        let containsNumber = /\d/.test(userGuess);
        let constainsSpecial = /[!?+@#€$';:=-]/.test(userGuess);

        const isInvalid = userGuess.length !== 5 || containsNumber === true || constainsSpecial === true;
        if(isInvalid && guessCount !== 10)
        {
            //If invalid user input, go to catch block.
            throw new Error("Invalid Guess");
        }

        if (guessCount === 1) 
        {
            guesses.innerHTML = 'Previous guesses:<br>';
        }

        //If user wins.
        if (userGuess === randomWord) 
        {
            lastResult.textContent = "Congratulations! You got it right!";
            lastResult.style.backgroundColor = "green";
            isInvalidFeedback.textContent = '';
            let upperUser = userGuess.toUpperCase();
    
            guesses.innerHTML += `Guess ${guessCount}. `

            //Display winning word.
            for(let i = 0; i < upperUser.length ; i++)
            {
                const letter = upperUser[i];
                guesses.innerHTML += ` ${letter}🟢`;
            }
            //Call set game over.
            setGameOver();
        } 
        //If user reaches maximum amount of guesses.
        else if (guessCount === 10) 
        {
            lastResult.textContent = "Game Over!";
            lastResult.style.backgroundColor = "red";
            isInvalidFeedback.textContent = '';

            let upperUser = userGuess.toUpperCase();
            let upperRandom = randomWord.toUpperCase();
    
            //If reaches 10 guesses on valid input.
            if(!isInvalid)
            {
                //Display final guess
                guesses.innerHTML += `Guess ${guessCount}. `
                for(let i = 0; i < upperUser.length; i++)
                {
                    const letter = upperUser[i];
                    if(upperUser[i] === upperRandom[i])
                    {
                        guesses.innerHTML += ` ${letter}🟢`;
                    }
                    else if(upperRandom.includes(letter))
                    {
                        guesses.innerHTML += ` ${letter}🟡`;
                    }
                    else
                    {
                        guesses.innerHTML += ` ${letter}🔴`;
                    }
    
                }
                guesses.innerHTML += `<br>`;
                setGameOver();
            }
            //if last guess is not valid
            else
            {
                throw error("Invalid");
            }
            
        } 
        else 
        {
            //User guess is incorrect. 
            lastResult.textContent = "Wrong!";
            lastResult.style.backgroundColor = "red";
            isInvalidFeedback.textContent = '';
    
            let upperUser = userGuess.toUpperCase();
            let upperRandom = randomWord.toUpperCase();

            guesses.innerHTML += `Guess ${guessCount}. `
    
            //Loop through characters to find which are in the word, and are correct.
            for(let i = 0; i < upperUser.length; i++)
            {
                const letter = upperUser[i];
                if(upperUser[i] === upperRandom[i])
                {
                    guesses.innerHTML += ` ${letter}🟢`;
                }
                else if(upperRandom.includes(letter))
                {
                    guesses.innerHTML += ` ${letter}🟡`;
                }
                else
                {
                    guesses.innerHTML += ` ${letter}🔴`;
                }
    
            }
            guesses.innerHTML += `<br>`;
        }

    }
    //Catch invalid input.
    catch(Error)
    {
        alert("Please enter a 5 letter word.");

        if (guessCount === 1) 
        {
            guesses.innerHTML = 'Previous guesses:<br>';
        }

        guesses.innerHTML += `Guess ${guessCount}. `

        guesses.innerHTML += 'Invalid Guess<br>';
        lastResult.textContent = "Please enter a 5 letter word";
        lastResult.style.backgroundColor = 'yellow';
        isInvalidFeedback.textContent = "Last guess was invalid."

        //If invalid input on the last guess, set game over.
        if(guessCount === 10)
        {
            lastResult.textContent = "Game Over!";
            lastResult.style.backgroundColor = "red";
            isInvalidFeedback.textContent = '';
            setGameOver();
        }
    }

    guessCount++;
    guessField.value = '';
    guessField.focus();
}

guessSubmit.addEventListener('click', checkGuess);

function setGameOver() {
    guessField.disabled = true;
    guessSubmit.disabled = true;
    restartButton = document.createElement('button');
    restartButton.textContent = "Start new game";
    document.body.appendChild(restartButton);
    restartButton.addEventListener('click', resetGame);
}

function resetGame() {
    guessCount = 1;

    const resultParas = document.querySelectorAll(".results p");
    for (let i = 0; i < resultParas.length; i++) {
        resultParas[i].textContent = '';
    }

    restartButton.remove();

    guessField.disabled = false;
    guessSubmit.disabled = false;
    guessField.value = '';
    guessField.focus();
    
    lastResult.style.backgroundColor = "white";
    randomIndex = Math.floor(Math.random() * 7);
    randomWord = words[randomIndex];
    console.log(`random word: ${randomWord}`);
    guessCount.textContent = '';
}

// alert("message") - pop-up with "message" as text
// V-- comparing strings --V
// guessField.value.toUpperCase() --> returns the value in guessField as uppercase letters
// guessField.value.toLowerCase() --> returns the value in guessField as lowercase letters
// guess[0] === secret[0] --> Checks if the first character in guess is the same as the first character in secret (strings)
// secret.includes(guess[0]) --> returns true if the first character in guess is in secret, returns false otherwise.