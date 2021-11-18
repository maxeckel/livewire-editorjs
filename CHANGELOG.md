# Changelog

All notable changes to `livewire-editorjs` will be documented in this file

## 1.3.0 2021-11-18

Renamed `init` method of alpine component to `initEditor` for Alpine v3 compatibility.

## 1.2.0 2021-10-23

Updated packaged EditorJS version to 2.22.3

## 1.1.0 2021-01-12

- Automatically json_decode the passed in "value" prop to an assoc array when a string is passed in.

## 1.0.0 2021-01-08

- Added option to disable the component of the package
- Added config option to rename the component of the package
- Added `placeholder` to the component
- Added `style` attribute to the component
- Added `logLevel` to the Editor.js config
- Added make command to create Editor.js components
- Added test suite

## 0.4.0 2021-01-04

- Added `readOnly` mode to the Editor.
- Lowered required Laravel version (7 & 8 supported)

## 0.3.0 - 2021-01-03

Renamed method name for image upload. 
Like this it is more consistent when the `attaches` plugin of Editor.Js will be added 

## 0.2.0 - 2021-01-03

Use `editorId` in the view as container id for the Editor.js instance

## 0.1.0 - 2021-01-03

Initial release of the package
