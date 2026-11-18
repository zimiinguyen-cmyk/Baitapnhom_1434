from pathlib import Path
from datetime import datetime
from uuid import uuid4
import xml.etree.ElementTree as ET
from flask import Flask, render_template, request, send_file

app = Flask(__name__)
BASE = Path(__file__).resolve().parent
OUTPUT = BASE / "outputs"
OUTPUT.mkdir(exist_ok=True)
FIELDS = ("full_name", "student_id", "class_name", "email", "year")


def read_schema():
    schema_path = BASE / "schemas" / "student.xsd"
    tree = ET.parse(schema_path)
    ns = {"xs": "http://www.w3.org/2001/XMLSchema"}
    fields = []
    for node in tree.findall(".//xs:sequence/xs:element", ns):
        fields.append({"name": node.attrib["name"],
                       "required": node.attrib.get("minOccurs", "1") != "0",
                       "type": node.attrib.get("type", "xs:string")})
    return fields


@app.route("/", methods=["GET", "POST"])
def index():
    fields = read_schema()
    if request.method == "GET":
        return render_template("index.html", fields=fields, error=None)
    values = {field["name"]: request.form.get(field["name"], "").strip()
              for field in fields}
    for field in fields:
        if field["required"] and not values[field["name"]]:
            return render_template("index.html", fields=fields,
                                   error=f"Thiếu trường: {field['name']}"), 400
    try:
        year = int(values["year"])
        if year <= 0:
            raise ValueError("Năm học phải lớn hơn 0")
    except ValueError:
        return render_template("index.html", fields=fields,
                               error="year phải là số nguyên dương"), 400
    student = ET.Element("student")
    for field in fields:
        key = field["name"]
        if values[key] or field["required"]:
            ET.SubElement(student, key).text = values[key]
    tree = ET.ElementTree(student)
    ET.indent(tree, space="  ")
    filename = f"student_{datetime.now():%Y%m%d_%H%M%S}_{uuid4().hex[:8]}.xml"
    path = OUTPUT / filename
    tree.write(path, encoding="utf-8", xml_declaration=True)
    return send_file(path, as_attachment=True, download_name=filename,
                     mimetype="application/xml")


if __name__ == "__main__":
    app.run(host="127.0.0.1", port=5000, debug=True)