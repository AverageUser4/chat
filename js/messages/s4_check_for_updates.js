"use strict";

/* wait for new messages and append them to output wrapper */

const myInterval = setInterval(isVarDefined, 1000);
let event_source;
let failure_count = 0;
let connection_open = false;

const active_users = document.getElementById('activeUsersList');

function updateActiveUsers(event) {
  const json = JSON.parse(event.data);
  let active_users_inner = '';
  
  for(let x of json) {
    active_users_inner += '<li>';
    active_users_inner += x[0];
    active_users_inner += '</li>';
  }

  active_users.innerHTML = active_users_inner;
}

function isVarDefined() {
  /*
  wait for latest_message_id to get defined
  connect or try to reconnect after error
  */
  if(latest_message_id === undefined)
    return;

  clearInterval(myInterval);

  event_source = new EventSource
  (`../php/messages/check_for_updates.php?latest=${latest_message_id}&user=${user.username_encoded}`);
  event_source.addEventListener('new_msg', appendNewMessages);
  event_source.addEventListener('custom_error', handleCustomError);
  event_source.addEventListener('open', () => connection_open = true);
  event_source.addEventListener('error', unableToConnect);
  event_source.addEventListener('active_update', updateActiveUsers);
}

function appendNewMessages(event) {
  /* 
  append new messages sent by server
  at the bottom of output field
  */
  const arr = event.data.split('%');

  if(arr.length < 3) {
    alert('Coś poszło nie tak.')
    return;
  }

  for(let i = 0; i < arr.length; i += 3) {
    createMessageElement(arr[i], parseMessageDate(arr[i + 2]), arr[i + 1], true);
  }
}

function handleCustomError(event) {
  /*
  - if php script timed out reconnect immediately
  - if wrong message id or username was provided don't try
  to reconnect
  - if there was another problem try to reconnect after 3 seconds,
  if it occurs 3 times, wait for 5 minutes before reconnecting
  */
  if(event.data != '')
    latest_message_id = event.data;

  event_source.close();

  let wait_for = 3000;
    
  if(event.lastEventId === 'wrong_data') {
    console.log("Podane dane są nieprawidłowe.");
    return;
  }
  
  if(event.lastEventId === 'timeout') {
    failure_count = 0;
    wait_for = 0;
  }

  if(++failure_count >= 3) {
    failure_count = 0;
    console.log(event.lastEventId);
    setTimeout(isVarDefined, 300000);
    return;
  }

  setTimeout(isVarDefined, wait_for);
}

function unableToConnect() {
  /*
  if open event wasn't run, we weren't able to
  connect to server (wait for 5 minutes and
  try to connect again) 
  */
  if(connection_open) {
    connection_open = false;
    return;
  }

  setTimeout(isVarDefined, 300000);
  console.log("Nie udało się nawiązać połączenia z serwerem.");
}
