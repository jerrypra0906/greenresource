import os

try:
    from docx import Document
except ImportError:
    raise SystemExit(
        "Missing dependency 'python-docx'. Install it with:\n\n"
        "    pip install python-docx\n"
    )


def extract_docx_to_md(src_path: str, dest_path: str) -> None:
    if not os.path.exists(src_path):
        print(f"Source file not found: {src_path}")
        return

    doc = Document(src_path)
    lines = []

    for para in doc.paragraphs:
        text = para.text.strip()
        if text:
            lines.append(text)

    content = "\n\n".join(lines)

    os.makedirs(os.path.dirname(dest_path), exist_ok=True)
    with open(dest_path, "w", encoding="utf-8") as f:
        f.write(content)

    print(f"Extracted '{src_path}' -> '{dest_path}'")


def main() -> None:
    base_docs_dir = os.path.join(os.path.dirname(os.path.dirname(__file__)), "docs")

    mappings = [
        (
            os.path.join(base_docs_dir, "Product Requirement Document.docx"),
            os.path.join(base_docs_dir, "Product_Requirement_Document_extracted.md"),
        ),
        (
            os.path.join(base_docs_dir, "Technical Documentation.docx"),
            os.path.join(base_docs_dir, "Technical_Documentation_extracted.md"),
        ),
    ]

    for src, dest in mappings:
        extract_docx_to_md(src, dest)


if __name__ == "__main__":
    main()


