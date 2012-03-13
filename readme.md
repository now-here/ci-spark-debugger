### Introduction

Debugger is a spark that provides flexible debugging functionality inside CodeIgniter applications.

### Installation

First, you will need to [install the sparks package manager][]. Once this is complete, change directory to the root of your CodeIgniter application and issue the following command `php tools/spark install debugger`. You're on fire!

To load Debugger into your application use `$this->load->spark('debugger/x.x.x')` (see [tags][] for available version numbers). The debugger object will now be accessible via `$this->debugger`.

### Usage

**Debugger::bindObserver($observer)**  
*Bind an observer to the debug object by name*  
*$observer - (string) The observer class name i.e. Log, Database*

Bound observers will be notified by the Debugger in the event that Debugger::debug() is called, either manually, or by the DebugException handler. At least one observer should be bound to the debug session in order for it to provide the functionality intended. Custom observers may be added and must implement the ObserverInterface in order to be loaded. Non-existent observers, or classes that do not implement the ObserverInterface simply won't be  bound.

**Debugger::addBreakpoint($message, $data)**  
*Add a breakpoint to the debug session*  
*$message - (string) Description of the debug breakpoint*  
*$data - (mixed) Data that will assist with the debugging process*

@todo Write function description

**Debugger::debug()**  
*Trigger the debugger on demand*

Calls the handle() function of all bound observers and collects their responses. A notification detailing how the debug session was handled (i.e. which observers were used, where log files were written to, database record ID) will be sent to all email addresses (`emailTo`) set in the spark configuration file (`config/debugger.php`). In production, triggering the debugger manually is *not* advised, though doing so will not stop application execution.

**Debugger::handleException($e)**  
*The DebugException handler*  
*$e - (Exception) Exception to be handled*

The debugger registers a custom exception handler. In the event a `DebugException` is thrown, the handler will call `Debugger::debug()`, stop execution and display the debug view - this is the advised way to handle the logging of debug sessions and you should **not** call this function manually. Other types of exception will not be handled, instead the previous handler (default or other custom handler) will be restored and the exception will be re-thrown.

[install the sparks package manager]: http://getsparks.org/install
[tags]: https://github.com/now-here/ci-spark-debugger/tags
