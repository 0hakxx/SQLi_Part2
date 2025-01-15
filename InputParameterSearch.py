import requests
from bs4 import BeautifulSoup
from urllib.parse import urljoin

def get_all_page_elements(url):
    response = requests.get(url)
    soup = BeautifulSoup(response.text, 'html.parser')
    
    page_elements = []
    
    # 입력 요소 추출
    inputs = soup.find_all('input')
    for input_tag in inputs:
        element = {
            'type': 'input',
            'name': input_tag.get('name'),
            'input_type': input_tag.get('type'),
            'value': input_tag.get('value')
        }
        page_elements.append(element)
    
    # 버튼 추출
    buttons = soup.find_all('button')
    for button in buttons:
        element = {
            'type': 'button',
            'name': button.get('name'),
            'id': button.get('id'),
            'text': button.text.strip()
        }
        page_elements.append(element)
    
    # submit 타입 input 추출
    submit_inputs = soup.find_all('input', type='submit')
    for submit in submit_inputs:
        element = {
            'type': 'submit_input',
            'name': submit.get('name'),
            'value': submit.get('value')
        }
        page_elements.append(element)
    
    # 테이블 헤더(th) 내 링크 추출
    th_links = soup.find_all('th')
    for th in th_links:
        a_tag = th.find('a')
        if a_tag:
            element = {
                'type': 'th_link',
                'text': a_tag.text.strip(),
                'href': urljoin(url, a_tag.get('href')),
                'class': a_tag.get('class')
            }
            page_elements.append(element)
    
    return page_elements

# 사용 예시
url = "http://192.168.204.1/board/mysql/index.php"
elements = get_all_page_elements(url)

for element in elements:
    if element['type'] == 'input':
        print(f"Input - Name: {element['name']}, Type: {element['input_type']}, Value: {element['value']}")
    elif element['type'] == 'button':
        print(f"Button - Name: {element['name']}, ID: {element['id']}, Text: {element['text']}")
    elif element['type'] == 'submit_input':
        print(f"Submit Input - Name: {element['name']}, Value: {element['value']}")
    elif element['type'] == 'th_link':
        print(f"Table Header Link - Text: {element['text']}, Href: {element['href']}, Class: {element['class']}")
