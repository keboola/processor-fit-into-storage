# processor-csv-wrap

[![Build Status](https://travis-ci.com/keboola/processor-csv-wrap.svg?branch=master)](https://travis-ci.com/keboola/processor-csv-wrap)

Takes all files (or sliced tables) in /data/in/tables and /data/in/files, wraps the entire file into a CSV.
The result files are in /data/out/tables or /data/out/files depending on where the source was. 
Manifests (if present) are copied without any change. Sliced files are supported. The processor is useful for
importing non-csv text (e.g. JSONs) files for later processing. The output CSV file will contain the columns:
`contents`, `file`, `index`. 

# Usage

The processor takes these options:
- `chunk_size` - number of characters for a single CSV table cell, see below.

Each file is completely read and wrapped into a CSV so that it becomes a single table cell. Because the
maximum cell size is usually limited (depending on where the file is imported afterwards), chunking is 
used to split larger files into cells. To be able to reconstruct each file, the columns `file` and `index` are added
to the CSV. For example when processing the file `greeting.txt`:

    Hi there

with `chunk_size` set to 3, the result CSV will be:

```
"contents","file","index"
"Hi ","greeting.txt","0"
"the","greeting.txt","1"
"re","greeting.txt","2"
```

Which corresponds to the following table:

|contents|file|index|
|---|---|---|
|Hi |greeting.txt|0|
|the|greeting.txt|1|
|re|greeting.txt|2|



## Development
 
Clone this repository and init the workspace with following command:

```
git clone https://github.com/keboola/processor-csv-wrap/
cd processor-csv-wrap
docker-compose build
docker-compose run --rm dev composer install --no-scripts
```

Run the test suite using this command:

```
docker-compose run --rm dev composer tests
```
 
# Integration

For information about deployment and integration with KBC, please refer to the [deployment section of developers documentation](https://developers.keboola.com/extend/component/deployment/) 
