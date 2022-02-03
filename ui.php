<!doctype html>
<html>
<head>
<title>Pengin UI</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script> 
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" defer></script> 
<script type="text/javascript" src="/js/index.js"></script>
<link rel="stylesheet" href="/css/index.css"/>
<style>
html, body {
    padding: 20px;
}
</style>
</head>
<body>
<?php
if ( $user_session ) include( "content/menus.php" );
?>
<div class="modals-container">
  <div class="modal" id="modal">
    <div class="modal-header">
      <div>
        <h2>Modal</h2>
      </div>
      <div>
        <div class="modal-close" data-modal="modal" tooltip="Close" data-placement="top">&times;</div>
      </div>
    </div>
    <p>Here is a modal...</p>
    <div class="modal-btn-group">
      <button type="submit" class="primary-btn"  data-modal="modal">OK</button>
    </div>
  </div>
  <div class="modal dialog" id="dialog">
    <h1>Dialog</h1>
    <p>Here is a dialog...</p>
    <div class="modal-btn-group">
      <button type="reset" class="grey-btn" data-modal="dialog">No</button>
      <button type="submit" data-modal="dialog">Yes</button>
    </div>
  </div>
</div>
<h1>Pengin UI</h1>
<br/>
<h3>Typography</h3>
<hr/>
<h1>Ubuntu</h1>
<h2>Heading 2</h2>
<h3>Heading 3</h3>
<p>This is how a standard paragraph displays</p>
<div class="subheader">Subheader</div>
<br/>
<div class="input-context">Subtext</div>
<br/>
<h3>UI elements</h3>
<hr/>
<button type="submit" class="primary-btn">Primary Button</button>
<button type="submit" class="grey-btn">Secondary Button</button>
<button type="submit" class="grey-btn icon-btn emoji-btn">icon button</button>
<button type="submit" class="grey-btn icon-btn icon-only-btn emoji-btn"></button>
<button type="submit" class="red-btn">Critical Button</button>
<button type="submit" disabled>Disabled Button</button>
<br/>
<br/>
<input type="text" placeholder="Text field"/>
<input type="text" placeholder="Disabled field" disabled/>
<input type="search" placeholder="Search field"/>
<input type="tel" placeholder="Telephone field"/>
<input type="email" placeholder="Email field"/>
<input type="text" placeholder="Date field" class="datepicker-fancy"/>
<input type="text" placeholder="Time field" class="timepicker"/>
<br/>
<br/>
<select>
  <option>HTML Select</option>
  <option>Option 2</option>
  <option>Option 3</option>
</select>
<select disabled>
  <option>Disabled Select</option>
</select>
<div class="dropdown" id="standard-select" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip">
  <div class="dropdown-label">Custom Dropdown</div>
  <div class="dropdown-menu" id="standard-select">
    <ul>
      <li id="standard-select">Option 1</li>
      <li id="standard-select">Option 2</li>
      <li id="standard-select">Option 3</li>
    </ul>
  </div>
</div>
<div class="dropdown dropdown-selected" id="selected-select" data-toggle="tooltip" data-placement="top" title="" data-original-title="Highlights selected">
  <div class="dropdown-label">Option 1</div>
  <div class="dropdown-menu" id="selected-select">
    <ul>
      <li id="selected-select" class="selected">Option 1</li>
      <li id="selected-select">Option 2</li>
      <li id="selected-select">Option 3</li>
    </ul>
  </div>
</div>
<div class="dropdown dropdown-static" id="static-select" data-toggle="tooltip" data-placement="top" title="" data-original-title="Label won't change">
  <div class="dropdown-label">Static Label</div>
  <div class="dropdown-menu" id="static-select">
    <ul>
      <li id="static-select">Option 1</li>
      <li id="static-select">Option 2</li>
      <li id="static-select">Option 3</li>
    </ul>
  </div>
</div>
<br/>
<br/>
<div class="checkbox" style="display: inline-block;">
  <input type="checkbox" id="standard-checkbox" name="standard-checkbox" checked>
  <label for="standard-checkbox">Checkbox</label>
</div>
<div class="checkbox" style="display: inline-block;">
  <input type="checkbox" id="standard-checkbox2" name="standard-checkbox2">
  <label for="standard-checkbox2">Checkbox 2</label>
</div>
<div class="checkbox disablethis" style="display: inline-block;">
  <input type="checkbox" id="standard-checkbox3" name="standard-checkbox3" disabled>
  <label for="standard-checkbox3">Checkbox 2</label>
</div>
<br/>
<br/>
<div>
  <div class="radio active-radio" style="display: inline-block;">
    <input type="radio" id="one" name="radios" checked>
    <label for="one">Option 1</label>
  </div>
  <div class="radio" style="display: inline-block;">
    <input type="radio" id="two" name="radios">
    <label for="two">Option 2</label>
  </div>
  <div class="radio disablethis" style="display: inline-block;">
    <input type="radio" id="three" name="radios" disabled>
    <label for="three">Disabled</label>
  </div>
</div>
<br/>
<hr/>
<div class="tabs" id="standard-tabs">
  <ul>
    <li id="one" class="active">One</li>
    <li id="two">Two</li>
    <li id="three">Three</li>
  </ul>
</div>
<div class="tab-page" id="one-tab">You're on tab 1... </div>
<div class="tab-page" id="two-tab" style="display: none;">You're on tab 2... </div>
<div class="tab-page" id="three-tab" style="display: none;">You're on tab 3... </div>
<br/>
<br/>
<h3>Modals, Dialogs, Toast</h3>
<hr/>
<button type="submit" class="secondary-btn" data-modal="modal">Modal</button>
<button type="submit" class="secondary-btn" data-modal="dialog">Dialog</button>
<button type="submit" class="secondary-btn" onclick="newAlert('Toast message');">Toast</button>
</body>
</html>