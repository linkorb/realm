REALM
=====

Realm is a framework for defining domain models.

It allows you to define:

* Concepts
* Codelists
* Mappings
* Section Types
* Resources
* Fusions

## Web-based realm project viewer

You can view Realm projects using the [realm-server](https://github.com/linkorb/realm-server).

## Configuration

Realm works with "projects" containing definitions.

1. copy `projects.yaml.dist` to `projects.yml`
2. edit `projects.yaml` to your situation.

You can use the `include_paths` array to specify one or more include paths that contain Realm project.
This is used by projects that specify a `<dependency />` element in their `realm.xml` file.

Use the `projects` array to specify one or more projects:

* `type`: `realm` (standard) or `decor` (for loading [decor](https://art-decor.org/) files)
* `filename`: path to the project's `realm.xml` file
* `unlisted`: optional, allows you to mark the project as unlisted on the viewer's frontpage. Direct links still work.

## License

MIT. Please refer to the [license file](LICENSE) for details.

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
