REALM
=====

Realm is a framework for defining domain models.

It allows you to define:

* Concepts: 
* Codelists
* Mappings
* Section Types
* Resources
* Fusions

## Running the viewer

  git clone git@github.com:linkorb/realm.git
  cd realm
  composer install
  php -S 0.0.0.0:8080 -t web/
  
The Realm viewer is now accessible using your browser by navigating to [http://127.0.0.1:8080](http://127.0.0.1:8080)

## Configuration

Realm works with "projects" containing definitions. To configure the viewer:

1. copy `app/config/projects.yml.dist` to `app/config/projects.yml`
2. edit `app/config/projects.yml' to your situation.

You can use the `include_paths` array to specify one or more include paths that contain Realm project.
This is used by projects that specify a `<dependency />` element in their `realm.xml` file.

Use the `projects` array to specify one or more projects:

* `type`: `realm` (standard) or `decor` (for loading [decor](https://art-decor.org/) files)
* `filename`: path to the project's `realm.xml` file
* `unlisted`: optional, allows you to mark the project as unlisted on the frontpage. Direct links still work.


## License

MIT. Please refer to the [license file](LICENSE) for details.

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
