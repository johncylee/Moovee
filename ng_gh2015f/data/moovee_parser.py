# -*- coding: utf-8 -*-

import csv
import sys
import json
import datetime


def parse(tsv_filename):
    with open(tsv_filename, 'r') as f:
        reader = csv.DictReader(f, dialect='excel-tab')
        return list(reader)


def process(movies):
    for movie in movies:
        datetime_str = movie['DATE'] + ' ' + movie['TIME']
        duration_mins = int(movie['DURATION'])
        start = datetime.datetime.strptime(datetime_str, '%Y/%m/%d %H:%M')
        end = start + datetime.timedelta(0, 0, 0, 0, duration_mins)
        movie['START_DATETIME'] = start.isoformat()
        movie['END_DATETIME'] = end.isoformat()
    return movies


def main():
    movies = parse(sys.argv[1])
    process(movies)
    print(json.dumps({'items': movies}, indent = 2, ensure_ascii = False))


if __name__ == '__main__':
    if len(sys.argv) > 1:
        main()
    else:
        print ''
        print '\tpython ' + sys.argv[0] + ' <FILENAME>'
