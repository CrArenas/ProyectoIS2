from locust import HttpLocust,task

class ApiUser(HttpUser):
    @task
    def get_home(self):
        self.client.get("/api/orders")
    
    @task
    def post_data(self):
        self.client.post("/api/orders",json={"name":"value"})