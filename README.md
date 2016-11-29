# CaseStore



## What is it?

This is a Symfony app that lets you run a database of Case Studies.

It is designed for many disparate staff members to work on the staff database, with all edits tracked for safety.

## Projects

This app can host multiple projects. Each project has it's own set of data and configuration options.

One project can be set as the System Default ("is_system_default" column on "project" table in database). If so, when a user goes to the front page of the app they will be automatically redirected to this project.

## Case Studies

Each project holds a set of Case Studies.

## Outputs

Each project can also hold details of "Outputs". An Output is something (A report, a press piece, etc) where a case study is used. Case Studies can be linked to Outputs, and in that way, the usage of Case Studies is tracked.

## Fields

Each project can have their own set of fields defined, both for "Case Studies" and "Outputs".

Each field has a type.

## Case Studies have users - and private data

Each case studies is linked to one or more users. These users are thought of as the "owners" of the case study.

Some fields and files can be marked as private, and for a particular case study these can only be seen as

Who these users are is made public on each case study. Thus if a user sees a case study they are interested in but they are not the owner, they know who to contact about it.

## Vagrant for development

Use Vagrant and Virtual Box for development

The app will be available on http://localhost:8080/app_dev.php

To run tests, log in with vagrant ssh and run

```
cd /vagrant; phpunit -c app/
```
