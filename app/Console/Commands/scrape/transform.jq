def group_to_name:
  if . == 1 then "endurance"
  elif . == 2 then "strength"
  elif . == 3 then "speed"
  elif . == 4 then "coordination"
  else "unknown"
  end;

.[] 
| .content[]
| .performance_conditions[]
| .data as $pc

| ($pc.age_group_id | tonumber) as $id
| ($pc.group_id | group_to_name) as $group

| {
    age_group_id: $id,
    lower_year: ($ages[0] | .[$id - 1].lower_year),
    category: $group
  }