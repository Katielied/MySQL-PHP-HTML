const STORAGE_KEY = "todo.tasks";

const newTaskInput = document.getElementById('newTaskInput');
const addBtn = document.getElementById('addBtn');
const taskList = document.getElementById('taskList');
const emptyState = document.getElementById('emptyState');
const stats = document.getElementById('stats');
const filters = document.querySelectorAll('.filter');
const clearCompletedBtn = document.getElementById('clearCompletedTasks');

let tasks = loadTasks();
let currentFilter = 'all';
let editText; 

function loadTasks(){
    try {
        const data = localStorage.getItem(STORAGE_KEY);
        return data ? JSON.parse(data) : [];  // If no data to parse, return empty array
    } catch(e) {
        console.error('Could not parse tasks from localStorage', e);
        return [];
    }
}

// Convert tasks array to JSON string and store in localStorage
function saveTasks() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(tasks)); 
}

// Unique Identifier
function uid(){
    return Date.now().toString(36) + Math.random().toString(36).slice(2,8);
}

// Create a "task" object, store it in tasks, then save tasks to localStorage (saveTasks()).
function addTask(text) {
    const task = {id: uid(), text: text.trim(),  completed: false, createdAt: Date.now()};
    tasks.unshift(task);
    saveTasks();
    render();
    console.log(`Task created successfully with uid of ${task.id}`);
}

// Update properties of a task based on its id.
function updateTask(id, updates){
    // findIndex(): returns the first index of tasks that matches the condition within function "x"
    const index = tasks.findIndex(x => x.id === id); 
    if(index === -1) return;
    // Assign properties from "updates" to our task.  
    // Use our task object for fallback properties where no changes were found.
    tasks[index] = Object.assign({}, tasks[index], updates);
    saveTasks();
    render();
}

// Remove a task based on its id.
function removeTask(id) {
    // Creates new tasks array by excluding the task that matches the given id.
    tasks = tasks.filter(x => x.id !== id);
    saveTasks();
    render();
}

// Remove all tasks that are marked as completed.
function clearCompleted(){
    tasks = tasks.filter(x => !x.completed);
    saveTasks();
    render();
}

// Set the current filter and update the UI to reflect the active filter.
function setFilter(filter){
    currentFilter = filter;
    // For each element in the filters div, toggle the "active" class if our selected filter
    // matches the filter data object of the selected element.
    filters.forEach(filteredElement => 
        filteredElement.classList.toggle('active', 
            filteredElement.dataset.filter === filter));
    render();
}

// Return tasks based on the current filter setting.
function filteredTasks(){
    if (currentFilter === 'active') return tasks.filter(task => !task.completed);
    if (currentFilter === 'completed') return tasks.filter(task => task.completed);
    return tasks;
}

// Render the task list based on the current tasks and filter.
function render() {
    taskList.innerHTML = '';
      const list = filteredTasks();
      if(list.length === 0){
        emptyState.style.display = 'block';
      } else {
        emptyState.style.display = 'none';
      }

      list.forEach(task => {
        const li = document.createElement('li');
        li.className = 'task';
        li.dataset.id = task.id;

        const left = document.createElement('div');
        left.className = 'left';

        const cbWrap = document.createElement('button');
        cbWrap.className = 'checkbox' + (task.completed ? ' checked' : '');
        const cb = document.createElement('input');
        cb.type = 'checkbox';
        cb.checked = !!task.completed;
        cbWrap.appendChild(cb);
        cbWrap.addEventListener('click', () => updateTask(task.id, { completed: !task.completed }));

        left.appendChild(cbWrap);

        const span = document.createElement('span');
        span.className = 'task-text' + (task.completed ? ' completed' : '');
        span.textContent = task.text;
        span.title = task.text;
        left.appendChild(span);

        li.appendChild(left);

        const actions = document.createElement('div');
        actions.className = 'task-actions';

        const editBtn = document.createElement('button');
        editBtn.className = 'icon-btn';
        editBtn.innerHTML = '✏️';
        editBtn.title = 'Edit task';
        editBtn.addEventListener('click', () => enterEditMode(li, task));
        actions.appendChild(editBtn);

        const delBtn = document.createElement('button');
        delBtn.className = 'icon-btn';
        delBtn.innerHTML = '🗑️';
        delBtn.title = 'Delete task';
        delBtn.addEventListener('click', () => {
           removeTask(task.id);
        });
        actions.appendChild(delBtn);

        li.appendChild(actions);

        taskList.appendChild(li);
      });

      const total = tasks.length;
      const completed = tasks.filter(t => t.completed).length;
      stats.textContent = `${total} task${total!==1? 's':''} — ${completed} completed`;
}

function enterEditMode(listItem, task){
      // Clear the contents of the left div
    const leftDiv = listItem.querySelector(".left");
    leftDiv.innerHTML = "";
      
      // Create an input field for editing the task text
    const inputField = document.createElement("input");
    inputField.type = "text";
    inputField.value = task.text;
    inputField.id = "newTaskInput"
    

      // Append the input field to the left div
    leftDiv.appendChild(inputField);

      // Create Save and Cancel buttons
    const saveBtn = document.createElement("button");
    saveBtn.className = "icon-btn";
    saveBtn.innerHTML = "💾";

    const cancelBtn = document.createElement("button");
    cancelBtn.className = "icon-btn";
    cancelBtn.innerHTML = "❌";

      // Define saveEdit and cancelEdit functions
    function saveEdit()
    {
        // Get the trimmed value from the input field. Do not allow empty text.
        const newText = inputField.value.trim();

        if(newText)
        {
            // Update the task with the new text
            updateTask(task.id, {text: newText});
        }
        else
        {
            alert("New Task cannot be empty.");
        }
    }
    function cancelEdit()
    { 
        // Which method do we use to render the app again?
        render();
    }

      // Attach event listeners to the save and cancel buttons
    saveBtn.addEventListener('click',saveEdit);
    cancelBtn.addEventListener('click',cancelEdit);

      // Append the buttons to the left div
    leftDiv.appendChild(saveBtn);
    leftDiv.appendChild(cancelBtn);

      // Focus the text in the input field
    inputField.focus();
    }

    addBtn.addEventListener('click', () => {
      const text = newTaskInput.value;

      // Only add task if text is not empty or whitespace
      if(text.trim()){
        addTask(text);
        newTaskInput.value = '';
        newTaskInput.focus();
      }
    });

    newTaskInput.addEventListener('keydown', (e) => {
      if(e.key === 'Enter') addBtn.click();
    });

    filters.forEach(f => f.addEventListener('click', () => setFilter(f.dataset.filter)));

    clearCompletedBtn.addEventListener('click', () => {
      clearCompleted();
    });

    render();


window.__todo = { addTask, removeTask, updateTask, loadTasks, saveTasks };