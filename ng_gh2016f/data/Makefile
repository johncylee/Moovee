JSONFILES=collision.json group_CATEGORY.json group_CTITLE.json \
group_DATE.json group_GRADE.json group_PLACE.json group_REMARK.json \
movies.json

.PHONY: all
all: data.json data.txt $(JSONFILES)

data.json: data.txt
	python moovee_parser.py $< > $@

data.txt:
	python moovee_scrap.py > $@

$(JSONFILES): data.txt
	php ../../includes/prepare.php $<

.PHONY: clean
clean:
	rm -f $(JSONFILES)
