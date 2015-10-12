# -*- coding: utf-8 -*-
#
# Copyright 2015, John Lee <john@0xlab.org>
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

import csv
import sys
import urllib
import urlparse
import datetime
import re

from bs4 import BeautifulSoup


def strip(c):
    if c in ('\r', '\t', '\n'):
        return False
    return True


def parse_movie(url, movie):
    f = urllib.urlopen(url)
    bs = BeautifulSoup(f, 'html5lib')
    movie['ETITLE'] = bs.find('span', class_='h6').text.strip()
    scripts = bs.body.find_all('script')
    parent_id = re.search( \
        '\$\(\'#parent_id\'\)\.val\(\'([0-9]+)\'\)\.change\(\);', \
        scripts[4].text \
    ).group(1)
    ghff_id = re.search( \
        '\$\(\'#ghff_id\'\)\.val\(\'([0-9]+)\'\)\.change\(\);', \
        scripts[4].text \
    ).group(1)
    film_id = re.search( \
        '\$\(\'#film_id\'\)\.val\(\'([0-9]+)\'\);', \
        scripts[4].text \
    ).group(1)
    url = 'http://www.goldenhorse.org.tw/api/film/search'
    data = urllib.urlencode({
        'action': 'get_class_list',
        'search_year': '2015',
        'parent_id': parent_id,
        'ghff_id': ghff_id,
        'film_id': film_id,
    })
    f = urllib.urlopen(url, data=data)
    import json
    jsonobj = json.load(f)
    bs = BeautifulSoup(jsonobj['ghff_list'], 'html5lib')
    movie['CATEGORY'] = bs.find('option', selected='selected').text
    movie['PAGE'] = 'NA'
    return movie


def parse_schedule(url, date, writer):
    f = urllib.urlopen(url)
    bs = BeautifulSoup(f, 'html5lib')
    tables = bs.body.find_all('table', class_='table special show-list')
    for table in tables:
        rows = table.find_all('tr')
        theater = rows[0].text.strip()
        rows.pop(0)
        for row in rows:
            movie = {'DATE': date}
            columns = row.find_all('td')
            hall = columns[0].text.strip()
            movie['PLACE'] = u'{0} {1}'.format(theater, hall)
            movie['TIME'] = columns[1].text.strip()
            ss = [x.strip() for x in columns[2].text.split('|')]
            if len(ss) == 4:
                movie['CTITLE'], movie['GRADE'], duration, movie['REMARK'] \
                    = ss
            elif len(ss) == 3:
                movie['CTITLE'], movie['GRADE'], duration \
                    = ss
                movie['REMARK'] = ''
            else:
                raise Exception()
            movie['DURATION'] = duration[:duration.rindex(u'分')]
            link = columns[2].a['href']
            movie = parse_movie(link, movie)
            writer.writerow(movie)


class UTF8DictWriter:

    def __init__(self, *args, **kwds):
        self.writer = csv.DictWriter(*args, **kwds)

    def writerow(self, row):
        output = dict(row)
        for k, v in row.iteritems():
            if type(v) == unicode:
                output[k] = v.encode('utf-8')
        self.writer.writerow(output)

    def writeheader(self):
        self.writer.writeheader()


def main():
    fields = ('PLACE', 'DATE', 'TIME', 'DURATION', 'CATEGORY', 'CTITLE',
              'ETITLE', 'GRADE', 'REMARK', 'PAGE')
    writer = UTF8DictWriter(sys.stdout, fields, dialect='excel-tab')
    writer.writeheader()
    for day in xrange(5, 27):
        date = datetime.date(2015, 11, day).isoformat()
        url = 'http://www.goldenhorse.org.tw/film/programme/schedule/?atc=0&search_date={0}'.format(date)
        parse_schedule(url, date, writer)


if __name__ == '__main__':
    main()
