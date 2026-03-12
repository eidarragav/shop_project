import firebase_admin
from firebase_admin import credentials, firestore
from config import Config

creden = credentials.Certificate(Config.FIREBASE_CREDENTIALS)
firebase_admin.initialize_app(creden)

db = firestore.client()