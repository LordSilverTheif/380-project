//grabbing stuff from the html that can be changed in the java code
const gameBoard = document.querySelector("#gameBoard");
const ctx = gameBoard.getContext("2d");
const scoreText = document.querySelector("#scoreText");
const resetBtn = document.querySelector("#resetBtn");

//sets the size for the game
const gameWidth = gameBoard.width;
const gameHeight = gameBoard.height;

//sets the colors for backgroun and snake
const boardBackground = "lightgray";
const snakeColor = "#90EE90";//"lightgreen";
const snakeBorder = "black";
const foodColor = "crimson";
const foodBorder = "black";

//set size
const unitSize = 25;

//determines if the game is running
let running = false;
let game_state= 'Start';

//sets velocity for the snake and food position
let xVelocity = unitSize;
let yVelocity = 0;
let foodX;
let foodY;

//scores
let score = 0;

//Changing the snakes color residual code
var letters = "0123456789ABCDEF";
var color = '#';
var changecolor= 0;

//creates the snake and sizes it
let snake = [
    {x:unitSize * 4, y:0},
    {x:unitSize * 3, y:0},
    {x:unitSize * 2, y:0},
    {x:unitSize, y:0},
    {x:0, y:0}
];

//Adds event listeners
window.addEventListener("keydown", changeDirection);
resetBtn.addEventListener("click", ()=>{
    alert("New color: "+snakeColor)
}
);

document.addEventListener("keydown", e =>{
    if(e.code == 'Enter' && game_state != "Play"){
        game_state= 'Play';
        gameStart();
        createFood();
        drawFood();
    }else if(e.code === "KeyR" && game_state === "Play"){
        resetGame();
    }else{

    }
})



//starts the game when called
function gameStart(){
    running = true;
    scoreText.textContent = "Score: "+score;
    createFood();
    drawFood();
    nextTick();
};

//determines the next tick to make the game run smoothly
function nextTick(){
    if(running){
        setTimeout(()=>{
        clearBoard();
        drawFood();
        moveSnake();
        drawSnake();
        checkGameOver();
        nextTick();
    }, 100);
}
else{
    displayGameOver();
}
};

//clears the board leaving a blank slate
function clearBoard(){
    ctx.fillStyle = boardBackground;
    ctx.fillRect(0 ,0, gameWidth, gameHeight);

};

//creates the food to be at random points on canvas
function createFood(){
    function randomFood(min, max){
        const randNum = Math.round((Math.random() * (max - min) + min) / unitSize) * unitSize;
        return randNum;
    }
    foodX = randomFood(0, gameWidth - unitSize);
    foodY = randomFood(0, gameWidth - unitSize);
    console.log(foodX);

};

//draws the snakes food on the screen everytime its eaten
function drawFood(){
    ctx.fillStyle = foodColor;
    ctx.strokeStyle = foodBorder;
    ctx.fillRect(foodX, foodY, unitSize, unitSize);
    ctx.strokeRect(foodX, foodY, unitSize, unitSize);

};

//moves the snake around smoothly on the screen
function moveSnake(){
    const head = {x: snake[0].x + xVelocity, y: snake[0].y + yVelocity};
    snake.unshift(head);
    if(snake[0].x == foodX && snake[0].y == foodY){
        score += 1;
        scoreText.textContent = "Score: "+score;
        createFood();
        // changecolor += 1;
        
    }
    else{
        snake.pop();
    }
    // if(changecolor == 20){
    //     snakeColor();
    //     changecolor=0;
    // }
};

//Draws the snake when it is called
function drawSnake(){
    ctx.fillStyle = snakeColor;
    ctx.strokeStyle = snakeBorder;
    snake.forEach(snakePart => {
        ctx.fillRect(snakePart.x, snakePart.y, unitSize, unitSize);
        ctx.strokeRect(snakePart.x, snakePart.y, unitSize, unitSize);
    })
};

//Changes the direction the snake is moving when a certain key is pressed
function changeDirection(event){
    const keyPressed = event.keyCode;
    console.log(keyPressed);
    const LEFT = 37;
    const UP = 38;
    const RIGHT = 39;
    const DOWN = 40;

    const goingUp = (yVelocity == -unitSize);
    const goingDown = (yVelocity == unitSize);
    const goingRight = (xVelocity == unitSize);
    const goingLeft = (xVelocity == -unitSize);

    switch(true){
        case(keyPressed == LEFT && !goingRight):
            xVelocity = -unitSize;
            yVelocity = 0;
            break;
        case(keyPressed == UP && !goingDown):
            xVelocity = 0;
            yVelocity = -unitSize;
            break;
        case(keyPressed == RIGHT && !goingLeft):
            xVelocity = unitSize;
            yVelocity = 0;
            break;
        case(keyPressed == DOWN && !goingUp):
            xVelocity = 0;
            yVelocity = unitSize;
            break;
    }
};

//Determines when the game should end
function checkGameOver(){
    switch(true){
        case (snake[0].x < 0):
            running = false;
            break;
        case (snake[0].x >= gameWidth):
            running = false;
            break;
        case (snake[0].y < 0):
            running = false;
            break;
        case (snake[0].y >= gameHeight):
            running = false;
            break;
    }
    for(let i = 1; i < snake.length; i+=1){
        if(snake[i].x == snake[0].x && snake[i].y == snake[0].y){
            running = false;
        }
    }
};

//Displays the game over message when the player loses
function displayGameOver(){
    ctx.font = "50px Times New Roman";
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.fillText("In the Dead State!", gameWidth/2, gameHeight/2);
    ctx.font = "25px Times New Roman";
    ctx.fillText("Click reset to try again.", gameWidth/2, ((gameHeight/2) +50 ));
    running = false;
};

//Resets the game when called
function resetGame(){
    score = 0;
    xVelocity = unitSize;
    yVelocity = 0;
    snake = [
        {x:unitSize * 4, y:0},
        {x:unitSize * 3, y:0},
        {x:unitSize * 2, y:0},
        {x:unitSize, y:0},
        {x:0, y:0}
    ];
    game_state= 'Start';
};

//Changes the snakes color after a certain interval of time
let newColor = '#90EE90';
function SnakeColor(){
    try{
        for (var i = 0; i < 6; i++){
            newColor= color + letters[(Math.floor(Math.random() * 16))];
        }
        snakeColor = newColor;
    }catch(e){
        alert("Error: "+e +" when chaniging snakes color");
    }
    alert("New color: "+newColor);
}
