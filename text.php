import json
import argparse
import os

DATA_FILE = 'blood_donors.json'

def load_donors():
    if os.path.exists(DATA_FILE):
        with open(DATA_FILE, 'r') as f:
            return json.load(f)
    return []

def save_donors(donors):
    with open(DATA_FILE, 'w') as f:
        json.dump(donors, f, indent=4)

def add_donor(name, age, gender, blood_group, contact, location):
    donors = load_donors()
    donor = {
        'id': len(donors) + 1,
        'name': name,
        'age': age,
        'gender': gender,
        'blood_group': blood_group.upper(),
        'contact': contact,
        'location': location
    }
    donors.append(donor)
    save_donors(donors)
    print(f"[+] Donor '{name}' added successfully.")

def view_donors():
    donors = load_donors()
    if not donors:
        print("[-] No donor records found.")
        return
    for d in donors:
        print(f"ID: {d['id']} | Name: {d['name']} | Age: {d['age']} | Gender: {d['gender']} | "
              f"Blood Group: {d['blood_group']} | Contact: {d['contact']} | Location: {d['location']}")

def search_by_blood_group(blood_group):
    donors = load_donors()
    results = [d for d in donors if d['blood_group'] == blood_group.upper()]
    if not results:
        print(f"[-] No donors found with blood group '{blood_group.upper()}'.")
        return
    for d in results:
        print(f"ID: {d['id']} | Name: {d['name']} | Blood Group: {d['blood_group']} | Contact: {d['contact']}")

def search_by_location(location):
    donors = load_donors()
    results = [d for d in donors if location.lower() in d['location'].lower()]
    if not results:
        print(f"[-] No donors found in location '{location}'.")
        return
    for d in results:
        print(f"ID: {d['id']} | Name: {d['name']} | Location: {d['location']} | Contact: {d['contact']}")

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='Blood Donor Records CLI')
    subparsers = parser.add_subparsers(dest='command')

    # Add command
    add_parser = subparsers.add_parser('add', help='Add a new donor')
    add_parser.add_argument('--name', required=True)
    add_parser.add_argument('--age', type=int, required=True)
    add_parser.add_argument('--gender', required=True)
    add_parser.add_argument('--blood_group', required=True)
    add_parser.add_argument('--contact', required=True)
    add_parser.add_argument('--location', required=True)

    # View all donors
    view_parser = subparsers.add_parser('view', help='View all donors')

    # Search by blood group
    bg_parser = subparsers.add_parser('search-blood', help='Search donors by blood group')
    bg_parser.add_argument('--blood_group', required=True)

    # Search by location
    loc_parser = subparsers.add_parser('search-location', help='Search donors by location')
    loc_parser.add_argument('--location', required=True)

    args = parser.parse_args()

    if args.command == 'add':
        add_donor(args.name, args.age, args.gender, args.blood_group, args.contact, args.location)
    elif args.command == 'view':
        view_donors()
    elif args.command == 'search-blood':
        search_by_blood_group(args.blood_group)
    elif args.command == 'search-location':
        search_by_location(args.location)
    else:
        parser.print_help()
