# Webtrees Branch Export module

Branch Export is an advanced export module for the open source family tree software, Webtrees. It allows you to export a portion of the family tree, but with more flexibility than the built-in export features.

## Requirements

This module is tested with the latest stable version of Webtrees (1.7.9), as well as the 1.8.0 development version.

## Installation

Module files should be copied to [Webtrees root]/modules_v3/branch_export folder. 

Then, you must enable the module (Control Panel / Modules / Module Administration). Next time you load Webtrees, the new menu "Branch export" menu item will appear in the main menu. Click on it. It will tell you that it needs to initialize a data new table. Click "Perform Initialization". Now the module is functional.

*Note: The update should not harm your existing data in any way, but it is always a good idea to create a backup first.* 
*Note 2: You can only do this if you are logged in as administrator.*

## How Branch Export works

Branch export module helps you export a portion of a tree in a way that is not possible using the built-in export features.

Branch export traverses the entire tree, starting from a specific individual (called pivot point). First it will select the immediate relatives of the pivot individual (e. g. parents, children, spouses, siblings). Then recursively continues the traversal with their relatives, until it hits one of the predefined blocking individuals (called cutoff points), or all non-blocked individuals are added to the branch.

You can use an unlimited number of cutoff points.

## What records are included in the branch?

The pivot point is always included. Cutoff points are also included if they can be reached during the traversal, but the traversal algorithm will stop traversing the tree when it hits a cutoff point, and thus their relatives - that are not reachable using a different path - will not be included. If an individual is included in the branch, all linked records (families, media objects, sources, notes, repositories) are also added.

*Note: Exporting the content of the branch requires that the Clippings cart module is installed and activated.*

## What records can be used as pivot point?

Only individuals can be used as pivot point. Any individual in the tree can be used.

## What records can be used as cutoff points?

Only individuals and families can be used as cutoff points. Any individual or family in the tree can be used. Using a family as cutoff point is a shortcut for adding all individuals in that family as cutoff points.